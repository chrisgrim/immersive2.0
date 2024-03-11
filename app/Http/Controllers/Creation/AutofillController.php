<?php

namespace App\Http\Controllers\Creation;
use App\Http\Controllers\Controller;
use App\Models\RemoteLocation;
use App\Models\Category;
use App\Models\Genre;
use App\Models\InteractiveLevel;
use App\Models\MobilityAdvisory;
use App\Models\AgeLimit;
use Illuminate\Http\Request;
use App\Models\Event;
use OpenAI;

class AutofillController extends Controller
{

/**
     * Updates a model.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function chat(Request $request)
    {

        $client = OpenAI::client(env('OPENAI_API_KEY'));

        // Retrieve existing conversation from session (or initialize a new one)
        $conversation = $request->conversation;

        if (empty($conversation)) {

            $remoteLocations = RemoteLocation::where('admin', true)
                    ->orWhere('user_id', auth()->user()->id)
                    ->pluck('name'); 

            $tags = Genre::where('admin', true)
                    ->orWhere('user_id', auth()->user()->id)
                    ->pluck('name');

            $mobilityAdvisories = MobilityAdvisory::where('admin', true)
                    ->orWhere('user_id', auth()->user()->id)
                    ->pluck('mobilities');

            $categories = Category::all()->pluck('name');

            $agelimit = AgeLimit::all()->pluck('name');

            $interactiveLevels = InteractiveLevel::all()->pluck('name');

            $agelimitString = implode(', ', $agelimit->toArray());
            $tagsString = implode(', ', $tags->toArray());
            $mobilityAdvisoriesString = implode(', ', $mobilityAdvisories->toArray());
            $interactiveLevelsString = implode(', ', $interactiveLevels->toArray());
            $remoteLocationsString = implode(', ', $remoteLocations->toArray());
            $categoriesString = implode(', ', $categories->toArray());

            $additionalText = "Pretend you are an event reviewer. Analyze the event description above. If the description does not specify a value, please ask instead of assuming. Once we have all the values correct then return an object with values.
                'hasLocation': (boolean), 
                'location' object: 'venue' (name or null), 
                'hiddenLocationToggle' (true if location hidden), 
                'hiddenLocation' (explanation if toggle true),
                'remoteLocations': array of tags [".$remoteLocationsString."], 
                'category': one from list [".$categoriesString."],
                'tags': choose 4 from list [".$tagsString."] plus add 2 unique,
                'interactionLevel': one from [".$interactiveLevelsString."],
                'mobility_advisories': any tags from [".$mobilityAdvisoriesString."],
                'advisories' object: 'audience' (brief experience description),
                ‘age_limits': one from [".$agelimitString."],
                'sexual' (boolean), 
                'sexualDescription' (null or description).
                'wheelchairReady': boolean.";
            $modifiedPrompt = $request->prompt . $additionalText;
        } else {
            $modifiedPrompt = $request->prompt;
        }

        // Add the new user message to the conversation
        $conversation[] = ['role' => 'user', 'content' => $modifiedPrompt];

        // Send the updated conversation to OpenAI
        $response = $client->chat()->create([
            'model' => 'gpt-4',
            'messages' => $conversation,
        ]);

        // Append the AI response to the conversation
        foreach ($response->choices as $result) {
            $conversation[] = [
                'role' => 'assistant',
                'content' => $result->message->content
            ];
        }


        return response()->json([
            'full_conversation' => $conversation
        ]);


    }


}
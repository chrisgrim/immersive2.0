@extends('layouts.master')

@section('meta')
    <title>{{config('app.name')}}</title>
    <link href="{{ mix('/assets/app.css') }}" rel="stylesheet">
    <link href="{{ mix('/assets/app-lite.css') }}" rel="stylesheet">
@endsection 

@section('nav')
    @auth
        <vue-nav navtype="profile" :user= "{{auth()->user()}}"></vue-nav>
    @endauth
    @guest
        <vue-nav></vue-nav>
    @endguest
@endsection

@section('content')
    <div class="account-index">
        <section class="account-index-content">
            <h2>Introduction</h2>
            <p>
            Welcome to everythingimmersive.com/ (the "Website").
            </p>
            <p>These Terms and Conditions ("Agreement") are a legal agreement that explain the terms and conditions that all visitors, users, clients and customers ("You", or "Your") must comply with when using the Website. By using the Website, You agree to abide by all of the terms and conditions in this Agreement.</p>
            <p>This Agreements incorporates Our <a href="https://everythingimmersive.com/privacy-policy">Privacy Policy</a> and Disclaimer.</p>
            <p>These Terms and Conditions outline the rules and regulations for the use of everythingimmersive.com/.
            <strong>PLEASE READ THIS AGREEMENT CAREFULLY because it affects Your rights and liabilities under the law. By using or accessing the Website, You confirm that You have read and agreed to be bound by this Agreement. If You do not agree with this Agreement, You cannot use or access the Website.</strong>
            </p>
            <p>These Terms and conditions are governed by the law of California, USA</p><h2 id="tableofcontents">Contents</h2>
            <p>Click below to jump to any section</p>
            <ol type="1">
            <li><a href="#definitions"><strong>Definitions</strong></a>
            </li>
            <li><a href="#userrightsresponsibilities"><strong>User Rights and Responsibility</strong></a>
            <ol><li><a href="#userrightsresponsibilities-1">Acceptable use</a></li><li><a href="#userrightsresponsibilities-2">Prohibited Use</a></li></ol></li>
            <li><a href="#ourcontent"><strong>Our Content</strong></a>
            </li>
            <li><a href="#contenttrademarks"><strong>Trademarks/ No Endorsement</strong></a>
            </li>
            <li><a href="#usercontent"><strong>User Content</strong></a>
            </li>
            <li><a href="#usertakedown"><strong>Right To Takedown Content</strong></a>
            </li>
            <li><a href="#youraccount"><strong>Your Account</strong></a>
            </li>
            <li><a href="#websiteinformation"><strong>Website Information</strong></a>
            </li>
            <li><a href="#security"><strong>Security</strong></a>
            </li>
            <li><a href="#linksTo"><strong>Links to this Website</strong></a>
            </li>
            <li><a href="#linksFrom"><strong>Third Party Links</strong></a>
            </li>
            <li><a href="#intellectual"><strong>Intellectual Property Rights</strong></a>
            </li>
            <li><a href="#copyright"><strong>Copyright Notice</strong></a>
            </li>
            <li><a href="#disclaimer"><strong>Disclaimers</strong></a>
            </li>
            <li><a href="#liability"><strong>Limitation of Liability</strong></a>
            </li>
            <li><a href="#indemnity"><strong>Indemnity</strong></a>
            </li>
            <li><a href="#release"><strong>Release</strong></a>
            </li>
            <li><a href="#comms"><strong>Communication</strong></a>
            </li>
            <li><a href="#disputes"><strong>Dispute Resolution and Arbitration</strong></a>
            </li>
            <li><a href="#termination"><strong>Termination</strong></a>
            </li>
            <li><a href="#terminationconsequences"><strong>Consequences of Termination or Expiration</strong></a>
            </li>
            <li><a href="#revisions"><strong>Revisions</strong></a>
            </li>
            <li><a href="#forceMajeure"><strong>Force Majeure</strong></a>
            </li>
            <li><a href="#minimumagerequirements"><strong>Minimum Age Requirements</strong></a>
            </li>
            <li><a href="#general"><strong>General</strong></a>
            </li>
            <li><a href="#userfeedback"><strong>User Feedback</strong></a>
            </li>
            <li><a href="#californiausers"><strong>Notice for California Users</strong></a>
            </li>
            <li><a href="#contactus"><strong>Contact Us</strong></a>
            </li>

            </ol>
            <h2 id="definitions">1 Definitions</h2>
            <p>The following terminology applies to these Terms and Conditions, Privacy Statement and Disclaimer Notice and any or all agreements:</p>
            <p>"<strong>Account</strong>" refers to the account that You have to create in the Website to log in, use, and access some parts of the Website.</p>
            <p>"<strong>Company</strong>," "<strong>Us</strong>," "<strong>We</strong>," and "<strong>Our</strong>" refers to Everything Immersive.</p>
            <p>"<strong>Content</strong>" is the information displayed at the Website including without limitation text, data, code, images, photographs, graphics, software, designs and any other materials that are available at the Website.</p>
            <p>"<strong>Party</strong>" refer to either You, or Us, or any other party to this Agreement. "<strong>Parties</strong>" refer to both You and Us.</p>
            <p>"<strong>Services</strong>" refer to any work We perform for You.</p>
            <p>"<strong>Supply</strong>" means to provide the Goods or Services as per the Invoice.</p>
            <p>"<strong>You</strong>" and "<strong>Your</strong>" refer to the visitor, user, client, customer or person accessing the Website and thus accepting this Agreement.</p>
            <p>"<strong>User Content</strong>" refers to the content which has been created by Our users.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="userrightsresponsibilities">2 User Rights and Responsibility</h2><h3 id="userrightsresponsibilities-1">2.1 Acceptable use</h3>
            <ul>
            <li>Everything Immersive will not be held liable should the website be unavailable for any amount of time.</li>
            <li>You are responsible for the safety and confidentiality of your passwords and usernames. Everything Immersive has the right to terminate or suspend your username and password should we suspect non compliance by you.</li>
            <li>You are not a minor in the jurisdiction in which you reside.</li>
            <li>You will not use any meta tags or any other "hidden text" utilising Our name or trademarks without Our express written consent.</li>
            <li>The Website and/or any portion of the Website may not be reproduced, sold, resold, visited or otherwise exploited for any commercial purpose without Our express written consent.</li>
            <li>Your use and access of the Website does not include any right of resale or commercial use of the Website or its Contents; any collection and use of any product listings, descriptions, or prices; any derivative use of the Website or its Contents; any downloading or copying of account information for the benefit of another merchant; or any use of data mining, robots, or similar data gathering and extraction tools.</li>
            <li>You must use our website for lawful purposes only.</li>
            <li>We encourage a free and open exchange of ideas in a climate of mutual respect. Any action by a user that infringes on another user's right to use and enjoy the Website is prohibited. We reserve the right, but do not assume any obligation, to monitor Your online conduct to enforce this Agreement.</li>

            </ul>
            <h3 id="userrightsresponsibilities-2">2.2 Prohibited Use</h3>
            <p>While using our service the following terms must be adhered to:</p>
            <ul>
            <li>to transmit or send unsolicited or unauthorised advertising or promotional material in any form.</li>
            <li>Disrupt or attempt to disrupt Our servers in any way that could cause harm to the Website.</li>
            <li>Distribute any material that contains software viruses or any other computer code, files, or programs that are designed or intended to: (a) disrupt, damage, or limit the functioning of the Website, any software, hardware, or telecommunications equipment used in connection therewith; or (b) damage or obtain unauthorised access to any data or other information of any third party. </li>
            <li>Harass, abuse, threaten or incite violence towards any individual or group. </li>
            <li>in any way that breaks or breaches applicable local, national or international laws or regulations. </li>
            <li>in any way which is fraudulent or unlawful. </li>
            <li>Hold Yourself out as sponsored by, endorsed by, or affiliated with Us or the Website.</li>
            <li>Interfere with any other person's use of or the proper functioning of the Website. </li>
            <li>Misrepresent Your identity or impersonate any person.</li>
            <li>Modify, translate, adapt, edit, decompile, disassemble, or reverse engineer any software programs used by Us.</li>
            <li>Provide any information that is false, misleading or inaccurate.</li>
            <li>Use any portion of the Website for any unlawful purpose or otherwise fail to comply with all applicable domestic and international laws, statutes, ordinances and regulations.</li>
            <li>Use any portion of the Website for uploading, posting, e-mailing, transmitting or otherwise making available User Content that infringes any patent, trademark, trade secret, copyright or other proprietary rights of any party.</li>
            <li>Use any portion of the Website for uploading, posting, e-mailing, transmitting or otherwise making available User Content that is harmful to minors in any way, harassing, harmful, threatening, abusive, vulgar, obscene, defamatory, libelous, hateful, or racially, ethnically or otherwise objectionable.</li>
            <li>Use any portion of the Website for uploading, posting, e-mailing, transmitting or otherwise making available junk mail, commercial advertisements, or any other form of commercial solicitation.</li>
            <li>Violate the privacy of any third party, this includes, but is not confined to, posting personal information about another person or entity without consent.</li>

            </ul>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="ourcontent">3 Our Content</h2>
            <p>Except for User Content as defined in User Content clause below, all of the content featured or displayed on the Website, including, without limitation, text, graphics, photographs, images, moving images, sound, and illustrations ("Our Content"), are owned by Us, Our licensors, vendors, agents and/or Our content providers. All elements of the Website, including without limitation the general design and Our Content, are protected by trade dress, copyright, moral rights, trademark and other laws relating to intellectual property rights.</p>
            <p>The Website, Our Content and all related rights shall remain Our exclusive property or of Our licensors unless otherwise expressly agreed.</p>
            <p>You will not remove any copyright, trademark or other proprietary notices from material found on the Website.</p>
            <p>Except as may be otherwise indicated in specific documents within the Website or as permitted by copyright law, You are authorised to view, play, print and download copyrighted documents, audio and video found on Our Website for personal, informational, and noncommercial purposes only.</p>
            <p>Except as permitted by copyright law, You may not modify any of the materials and You may not copy, distribute, transmit, display, perform, reproduce, publish, license, create derivative works from, transfer or sell any information or work contained on the Website.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="contenttrademarks">4 Trademarks/ No Endorsement</h2>
            <p>All of Our trademarks, service marks and trade names used herein (including but not limited to: the Everything Immersive name, Everything Immersive logo, the Website name, the Website design, and any logos) (collectively "Marks") are Our trademarks or registered trademarks or of Our affiliates, partners, vendors or licensors. You may not use, copy, reproduce, republish, upload, post, transmit, distribute, or modify Our trademarks in any way, including in advertising or publicity pertaining to distribution of materials on the Website, without Our prior written consent. The use of Our trademarks on any other site or network computer environment is not allowed. We prohibit the use of Our trademarks as a "hot" link on or to any other site, unless We have approved in advance the establishment of such a link. You shall not use Our name or any language, pictures or symbols which could, in Our judgment, imply Our endorsement in any (i) written or oral advertising or presentation, or (ii) brochure, newsletter, book, or other written material of whatever nature, without prior written consent.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="usercontent">5 User Content</h2>
            <p>You may be able, as dictated by the functionality of the Website, to submit or to otherwise make available messages, e-mails, photographs, videos and other content for display on the Website ("User Content").</p>
            <p>You shall be solely responsible for Your own User Content and the consequences of posting or publishing them. The Website merely acts as a passive conduit for Your online distribution and publication of User Content.</p>
            <p>Without limiting any of Our rights in law and equity, We reserve the right to remove any User Content for any reason in Our sole discretion, including any User Content that We believe may violate this Agreement, or any copyright or third-party rights.</p>
            <p>By submitting or otherwise making available any User Content to the Website, You automatically grant Us a nonexclusive, royalty-free, worldwide, perpetual, irrevocable, sub-licensable (through multiple tiers), license to use, modify, copy, distribute, transmit, publicly display, publicly perform, publish, adapt, create derivative works of, distribute, transfer or sell any such content, for any purpose, including commercial purposes and in connection with advertising for the sole benefit of the Website (collectively, "Use"), in any type of media or in any form now known or later developed, without any payment to You. In addition, You automatically waive and give up any claim that any use of such content violates any of Your rights, including privacy rights, publicity rights, moral rights or any other right, including the right to approve the way the Website uses such content. You also grant Us and the Website the right to use any material, information, ideas, concepts, know-how, or techniques contained in any communication You provide, submit, or otherwise make available to the Website or to Us for any purpose whatsoever, including, without limitation, commercial purposes. By submitting User Content, You automatically warrant and represent to Us that You are the owner of all intellectual property rights in and to the User Content or that You otherwise have all sufficient rights to grant the license above.</p>
            <p>By submitting User Content, You further warrant and represent that the User Content infringes no third-party right of privacy, right of publicity, or any other third-party right or proprietary interest.</p>
            <p>User Content does not represent Our views or any individual associated with Us, and We do not control User Content. In no event shall You represent or suggest, directly or indirectly, Our endorsement of User Content. We do not vouch for the accuracy or credibility of any User Content on Our Website, and do not take any responsibility or assume any liability for any actions You may take as a result of reading User Content on Our Website.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="usertakedown">6 Right To Takedown Content</h2>
            <p>You shall remain solely responsible for Your User Content and We shall have no obligation to prescreen any such content. However, We shall have the right in Our sole discretion to edit, refuse to post, or remove any material submitted to or posted on the Website at any time without notice. Without limiting the foregoing, We shall have the right to remove any material that We find to be in violation of the provisions hereof or otherwise objectionable, and the additional right to deny any User who fails to conform to any provision of this Agreement access to the Website.</p>
            <p>If You wish to remove Your User Content from the Website, please send Us an email to contact@everythingimmersive.com. We will remove Your User Content within 10 business days of receiving Your request. However, We may retain copies of Your User Content, not accessible to the public, on Our backup servers even after You request removal.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="youraccount">7 Your Account</h2>
            <p>In consideration of Your use of the Website, You will:</p>
            <ul>
            <li>provide true, accurate, current and complete information about Yourself as prompted by the Website's registration page (such information being the “<strong>Account Data</strong>”); and</li>
            <li>maintain and promptly update the Account Data to keep it true, accurate, current and complete.</li>
            </ul>
            <p>You are entirely responsible for the security and confidentiality of Your password and Account. You will not share Your Account Data or Your username and password with any third party or permit any third party to login to the Website using Your Account Data. Furthermore, You are entirely responsible for any and all activities that occur under Your Account. You are responsible for taking precautions and providing security measures best suited for Your situation and intended use of the Services and Website.</p>
            <p>You agree to immediately notify Us of any unauthorised use of Your Account or any other breach of security of which You become aware.</p>
            <p>The Website may also include a tool that allows You to sign in or register using information from Your accounts in third party services, such as Facebook, Twitter, Google, Yahoo, or LinkedIn. These third-party services are not related to the Website, and Your use of such third-party services is subject to the terms and policies of those services.</p>
            <p>For more information on how We collect, store, use, and share Your Account Data, please check Our <a href="https://everythingimmersive.com/privacy-policy">Privacy Policy</a></p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="websiteinformation">8 Website Information</h2>
            <p>We attempt to ensure that information on the Website is complete, accurate and current. Despite Our efforts, the information on the Website may occasionally be inaccurate, incomplete or out of date. We make no representation as to the completeness, accuracy or currency of any information on the Website.</p>
            <p>Through Your use of the Website, You may be exposed to content that You may find offensive, objectionable, harmful, inaccurate or deceptive. By using Our Website, You assume all associated risks.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="security">9 Security</h2>
            <p>Information provided by you through everythingimmersive.com/ is stored on a secured private server. While we have taken reasonable precautions to protect your personal information, no security system is impenetrable and we cannot guarantee the security of information that you provide to us via the Internet, through our Website, through online ordering, or in databases stored on our servers. </p>
            <p>Our online ordering functionality employs Secure Sockets Layer (SSL) software, the industry standard and among the best software available today for secure commerce transactions. It encrypts all of your personal information. Please see our <a href="https://everythingimmersive.com/privacy-policy">Privacy Policy</a> for full details on security.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="linksTo">10 Links to this Website</h2>
            <p>Running or displaying the Website or any information or material displayed on the Website in frames or through similar means on another site without Our prior written permission is prohibited.</p>
            <p>We grant You a nonexclusive right to display at Your site any link that would permit any user to go from Your site to Our Website. You obtain no rights other than the right to link to Our Website. Further, We do not guarantee, approve, or endorse the information or products available on Your site.</p>
            <p>You agree to not use and display the links in such a manner that is misleading, defamatory, infringing, libellous, disparaging, obscene, or otherwise objectionable to Us. You agree to not use the links in any manner that is likely to reduce, diminish or damage the goodwill, value or reputation associated with Us. You warrant that Your site does not include any material, and does not contain links to sites displaying any material, which is harmful, pornographic, abusive, hateful, obscene threatening, defamatory, or which encourages illegal activity. Upon notice from Us, You shall promptly remove the links.</p>
            <p>If Your wish to obtain written consent from Us, please contact us using our <a href="https://everythingimmersive.com/contact">contact page</a></p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="linksFrom">11 Third Party Links</h2>
            <p>From time to time, the Website may contain links to sites that We and Our affiliates do not own, operate nor control. All such links are provided solely for Your convenience. If You use these links, You will leave the Website. Neither We nor any of Our respective affiliates are responsible for any content, material or other information located on or accessible from any other site. Neither We nor any of Our respective affiliates endorse, guarantee, or make any representations or warranties regarding any other site, or any content, materials or other information located or accessible from any other site, or the results that You may obtain from using any other site.</p>
            <p>We do not control, endorse, sponsor or approve of the third parties or their content not do We take any responsibility for any aspect of these websites or their content. If You decide to access any other site linked to or from this Website, You do so entirely at Your own risk. You agree to be aware when You leave the Website and to read the privacy statements of these sites. You will evaluate the security and trustworthiness of any site connected to or accessed through the Website before disclosing any personal information to them. We do not accept any responsibility for any loss or damage in whatever manner, howsoever caused, resulting from Your disclosure of personal information to those third-party sites.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="intellectual">12 Intellectual Property Rights</h2>
            <p>We claim copyright and all other intellectual property rights to all the material on the Website, including, but not limited to the words, information, graphics, designs, logos, trademarks, photographs, icons, drawings and text.</p>
            <p>Our intellectual property is protected under copyright, trademark and other intellectual property laws.</p>
            <p>As part of this Agreement, You agree to not reproduce, distribute, sell, publish or broadcast any of the material found on this Website without Our prior written consent.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="copyright">13 Copyright Notice</h2>
            <p>All Website design, graphics, text selections, arrangements, and all software are of Copyright © 2020, Everything Immersive or its licensors. ALL RIGHTS RESERVED.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="disclaimer">14 Disclaimers</h2>
            <p><strong>YOUR USE OF THE WEBSITE IS AT YOUR OWN RISK. THE INFORMATION, MATERIALS AND SERVICES PROVIDED ON OR THROUGH THE WEBSITE ARE PROVIDED AS IS WITHOUT ANY WARRANTIES OF ANY KIND INCLUDING WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE, SECURITY OR NON-INFRINGEMENT OF INTELLECTUAL PROPERTY. NEITHER WE NOR ANY OF OUR AFFILIATES WARRANT THE ACCURACY OR COMPLETENESS OF THE INFORMATION, MATERIALS OR SERVICES PROVIDED ON OR THROUGH THE WEBSITE. THE INFORMATION, MATERIALS AND SERVICES PROVIDED ON OR THROUGH THE WEBSITE MAY BE OUT OF DATE, AND NEITHER US, NOR ANY OF OUR AFFILIATES MAKE ANY COMMITMENT OR ASSUME ANY DUTY TO UPDATE SUCH INFORMATION, MATERIALS OR SERVICES. THE FOREGOING EXCLUSIONS OF IMPLIED WARRANTIES DO NOT APPLY TO THE EXTENT PROHIBITED BY LAW. PLEASE REFER TO YOUR LOCAL LAWS FOR ANY SUCH PROHIBITIONS.</strong></p>
            <p><strong>NO ADVICE OR INFORMATION, WHETHER ORAL OR WRITTEN, OBTAINED FROM THE WEBSITE OR THROUGH OUR SERVICES WILL CREATE ANY WARRANTY NOT EXPRESSLY MADE HEREIN.</strong></p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="liability">15 Limitation of Liability</h2>
            <p>We do not assume any responsibility, or will be liable, for any damages to, or any viruses that may infect Your computer, telecommunication equipment, or other property caused by or arising from Your access to, use of, or browsing the Website, or Your downloading of any information or materials from this Website. <strong>IN NO EVENT WILL WE, OR ANY OF OUR OFFICERS, DIRECTORS, EMPLOYEES, SHAREHOLDERS, AFFILIATES, AGENTS, SUCCESSORS OR ASSIGNS, NOR ANY PARTY INVOLVED IN THE CREATION, PRODUCTION OR TRANSMISSION OF THE WEBSITE, BE LIABLE TO YOU OR ANYONE ELSE FOR ANY INDIRECT, SPECIAL, PUNITIVE, INCIDENTAL OR CONSEQUENTIAL DAMAGES (INCLUDING, WITHOUT LIMITATION, THOSE RESULTING FROM LOST PROFITS, LOST DATA OR BUSINESS INTERRUPTION) ARISING OUT OF THE USE, INABILITY TO USE, OR THE RESULTS OF USE OF THE WEBSITE, ANY WEBSITES LINKED TO THE WEBSITE, OR THE MATERIALS, INFORMATION OR SERVICES CONTAINED ON ANY OR ALL SUCH WEBSITE, WHETHER BASED ON WARRANTY, CONTRACT, TORT OR ANY OTHER LEGAL THEORY AND WHETHER OR NOT ADVISED OF THE POSSIBILITY OF SUCH DAMAGES. YOU SPECIFICALLY ACKNOWLEDGE THAT WE SHALL NOT BE LIABLE FOR USER SUBMISSIONS OR THE DEFAMATORY, OFFENSIVE, OR ILLEGAL CONDUCT OF ANY THIRD PARTY, AND THAT THE RISK OF HARM OR DAMAGE FROM THE FOREGOING RESTS ENTIRELY WITH YOU. THE FOREGOING LIMITATIONS OF LIABILITY DO NOT APPLY TO THE EXTENT PROHIBITED BY LAW. PLEASE REFER TO YOUR LOCAL LAWS FOR ANY SUCH PROHIBITIONS.</strong></p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="indemnity">16 Indemnity</h2>
            <p>You agree to defend, indemnify and hold Us and any of Our affiliated company or individual harmless from any and all liabilities, costs, and expenses, including reasonable attorneys’ fees, related to or in connection with (a) the use of the Website or the internet or Your placement or transmission of any message or information on this Website by You or Your authorised users; (b) Your violation of any term of this Agreement, including without limitation, Your breach of any of the Representations and Warranties above; (c) Your violation of any third party right, including without limitation any right of privacy, publicity rights or Intellectual Property Rights; (d) Your violation of any law, rule or regulation of USA or any other country; (e) any claim or damages that arise as a result of any User Content that You provide to Us; or (f) any other party’s access and use of the Website with Your unique username, password or other appropriate security code.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="release">17 Release</h2>
            <p>In the event that You have a dispute with one or more other visitors, users, customers, or clients of the Website, You release Us (and Our officers, directors, agents, subsidiaries, joint ventures and employees) from claims, demands and damages (actual and consequential) of every kind and nature, known and unknown, suspected and unsuspected, disclosed and undisclosed, arising out of or in any way connected with such disputes.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="comms">18 Communication</h2>
            <p>We reserve the right to send You an electronic mail for the purpose of informing You of changes or additions to the Website or this Agreement.</p>
            <p>Except as disclosed in Our <a href="https://everythingimmersive.com/privacy-policy">Privacy Policy</a>, We will not monitor, edit, or disclose the contents of Your e-mail, unless required in the course of normal maintenance of the Website and its systems, or unless required to do so by law or in the good-faith belief that such action is necessary to: (a) comply with the law or comply with legal process served on Us or the Website; (b) protect and defend Our rights or property of, the Website, or the users of the Website; or (c) act in an emergency to protect the personal safety of Our guests, the Website, or the public.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="disputes">19 Dispute Resolution and Arbitration</h2>
            <p>You and We agree to the following dispute resolution process for any legal controversy or legal claim arising out of or relating to this Agreement, the Website, any subscription to the Website or any other aspect of Our relationship (“<strong>Subject Legal Claim</strong>”).</p>
            <p>To help resolve any issues between Us promptly and directly, You and We agree to begin any arbitration within 15 days after a Subject Legal Claim arises; otherwise, the Subject Legal Claim is waived.</p>
            <p>In an attempt to find the quickest and most efficient resolution of Our issues, You and We agree to first discuss any issue informally for at least 15 days. To do that, please send Your full name and contact information, Your concern and Your proposed solution by email to Us at . If We should need to discuss an issue with You, We will contact You using the email or mailing address on Your Account.</p>
            <p>If We do not reach an agreed upon solution after Our discussions for at least 15 days, You and We agree that any Subject Legal Claim that either of Us may have must be resolved through binding individual arbitration in California, USA in accordance with American Arbitration Association.</p>
            <p>There are two limited exceptions to this Dispute Resolution and Arbitration provision: (a) either Party may pursue in small claims court any action that is within that jurisdiction, as long as the case proceeds on an individual basis only; (b) either Party may seek to enforce its patents, trademarks, copyrights or trade secrets in an appropriate state or federal court.</p>
            <p>You and Us also agree to arbitrate in each of Our individual capacities only, not as a representative or member of a class, and each of Us expressly waives any right to file a class action or seek relief on a class basis.</p>
            <p>You may download a form Notice to initiate arbitration at&nbsp;<a href="https://www.adr.org/">www.adr.org</a>. If You initiate the arbitration, Your arbitration fees will be limited to the filing fee set forth in the AAA's Consumer Rules. It is important that You understand that the arbitrator's decision will be binding and may be entered as a judgment in any court of competent jurisdiction. In any action to enforce this Agreement, the prevailing party will be entitled to costs and attorneys' fees.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="termination">20 Termination</h2>
            <p>We may terminate this Agreement:</p>
            <ul>
            <li>for any or no reason during any free use of the Websites or Services;</li>
            <li>if You breach any of the terms of this Agreement, and that breach is not remediable;</li>
            <li>if You breach any of these terms of this Agreement and that breach is not remedied within 0 of receiving written notice of the breach;</li>
            <li>if You have failed to pay any due charges by the stated date.</li>
            </ul>
            <p>We may also block Your access to Our Website in the event that:</p>
            <ul>
            <li>You breach this Agreement;</li>
            <li>We are unable to verify or authenticate any information You provide to Us; or</li>
            <li>We believe that Your actions may cause financial loss or legal liability for You, Our users or Us.</li>
            </ul>
            <p>You may terminate Your use of the Website or the Services by contacting Us through our contact details in this policy. The termination request will be subject to Our verification of Your ownership of the Account.</p>
            <p>In terminating Your Account, You shall be liable to pay all fees and charges that have accrued up until the termination takes place. You are personally liable for any orders placed or charges incurred through Your Account prior to termination.</p>
            <p>Either Party may terminate the Agreement:</p>
            <p>Upon 0 days' written notice to the other party of a material breach, if such breach remains uncured at the expiration of such period;</p>
            <p>If the other party becomes the subject of petition in bankruptcy or any proceeding relating to insolvency, receivership, liquidation or assignment for the benefit of creditors.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="terminationconsequences">21 Consequences of Termination or Expiration</h2>
            <p>Upon the effective date of termination or expiration of this Agreement:</p>
            <ul>
            <li>All rights, Subscriptions and licenses granted to You shall be terminated immediately.</li>
            <li>You will immediately cease use of and/or access to the Account, Website, and the Services.</li>
            <li>We will suspend or terminate Your access to the Website and Services.</li>
            <li>We will suspend or terminate access to any or all of the data to the extent permitted by law</li>
            </ul>
            <p>The termination or expiry of this Agreement shall not affect any of Our accrued rights and liabilities at any time up until the date of termination.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="revisions">22 Revisions</h2>
            <p>This Agreement is subject to Our revision and changes from time to time. Your continued use of the Website after the revision and changes will mean that You agree, without qualification, to the revised Agreement.</p>
            <p>If You are not a registered Subscriber of the Website, then You can review revisions to this Agreement by regularly checking this page.</p>
            <p>Material revisions to this Agreement will be indicated by an updated effective date at the top left corner of this page and by a notice posted on the homepage of the Website.</p>
            <p>It is Your responsibility to remain apprised of any revisions to this Agreement and to remain in compliance therewith.</p>
            <p>Should You object to any such revisions to this Agreement or become dissatisfied with the Website in any way, Your only recourse is to immediately terminate Your membership or discontinue use of the Website. Otherwise, continuing to use, visit, access or use the Website after the effective date means that You agree to be bound by any and all revisions.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="forceMajeure">23 Force Majeure</h2>
            <p>We shall not be responsible for damages or for delays or failures in performance resulting from acts or occurrences beyond Our reasonable control, including, without limitation: fire, lightning, explosion, power surge or failure, water, acts of God, war, revolution, civil commotion or acts of civil or military authorities or public enemies; epidemics, pandemics; any law, order, regulation, ordinance, or requirement of any government or legal body or any representative of any such government or legal body; or labor unrest, including without limitation, strikes, slowdowns, picketing, or boycotts; inability to secure raw materials, transportation facilities, fuel or energy shortages, or acts or omissions of other common carriers.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="minimumagerequirements">24 Minimum Age Requirements</h2>
            <p>Our Website is not directed to children. Access to and use of Our Website is only for those who are at least at the age of <strong>13 years in the USA</strong> or <strong>16 years in the European Union</strong> or otherwise capable of entering into and performing legal agreements. If You are younger than this, You may not register for or use Our Website.</p>
            <p>Any person who registers as a user or provides their personal information to Our Website represents that they are at least at the age of <strong>13 years in the USA</strong> or <strong>16 years in the European Union</strong> or otherwise capable of entering into and performing legal agreements.</p>
            <p>In agreeing with this Agreement, You represent and warrant that You are at least at the age of <strong>13 years in the USA</strong> or <strong>16 years in the European Union</strong> or otherwise capable of entering into and performing legal agreements.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="general">25 General</h2>
            <p><strong>Jurisdiction and Choice of Law</strong>. Any claim relating to, and the use of, this Website and the materials contained herein is governed by the laws of the state of California, USA. You consent to the exclusive jurisdiction of the state and federal courts located in Los Angeles, California, USA.</p>
            <p><strong>Entire Agreement</strong>. This Agreement and the other agreements stipulated to be incorporated herein are the entire agreement between You and Us and supersede any prior understandings or agreements (written or oral).</p>
            <p>A printed version of this Agreement will be admissible in judicial and administrative proceedings based upon or relating to this Agreement to the same extent and subject to the same conditions as other business documents and records originally generated and maintained in printed form.</p>
            <p><strong>Severability</strong>. If any provision of this Agreement is held to be invalid or unenforceable, such provision shall be struck and the remaining provisions shall be enforced.</p>
            <p><strong>Assignment</strong>. You agree that this Agreement and all incorporated agreements may be automatically assigned by Us in Our sole discretion. You cannot assign this Agreement without Our written consent.</p>
            <p><strong>Headings</strong>. Headings are for reference purposes only and in no way define, limit, construe or describe the scope or extent of such section.</p>
            <p><strong>No Waiver</strong>. Our failure to act with respect to Your or others’ breach does not waive Our right to act with respect to subsequent or similar breaches.</p>
            <p><strong>Survival</strong>. Clauses “Trademarks/No Endorsement”, “Advertising Rights”, “Intellectual Property Rights”, “DISCLAIMERS”, “LIMITATIONS OF LIABILITY”, “Indemnity”, “Release”, “Communications”, and “Dispute Resolution and Arbitration” shall survive any termination or expiration of this Agreement.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="userfeedback">26 User Feedback</h2>
            <p>We welcome and encourage You to provide feedback, comments, and suggestions for improvements of the Website (“<strong>Feedback</strong>”). You may submit Feedback by emailing Us at contact@everythingimmersive.com. You acknowledge and agree that if You submit any Feedback to Us, You hereby grant to Us a non-exclusive, worldwide, perpetual, irrevocable, fully-paid, royalty-free, sub-licensable (through several tiers) and transferable license under any and all intellectual property rights that You own or control in relation to the Feedback to use, reproduce, view, communicate to the public by any means, print, copy (whether onto hard disk or other media), edit, translate, perform and display (publicly or otherwise), distribute, redistribute, modify, adapt, make, sell, offer to sell, transmit, license, transfer, stream, broadcast, create derivative works from, and otherwise use and exploit the Feedback for any purpose.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="californiausers">27 Notice for California Users</h2>
            <p>Under California Civil Code Section 1789.3, California users are entitled to the following specific consumer rights notice: You may contact Us at our <a href="https://everythingimmersive.com/contact">contact page</a>. The Complaint Assistance Unit of the Division of Consumer Services of the California Department of Consumer Affairs may be contacted in writing at 1625 N. Market Blvd., Suite S-202, Sacramento, California 95834, or by telephone at (800) 952-5210.</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="contactus">28 Contact Us</h2>
            <p>If You do not understand any of the foregoing terms and conditions or if You have any questions or comments, please contact Us through Everything Immersive, contact@everythingimmersive.com</p>
            <p><a href="#tableofcontents">Back to table of contents</a></p><p><strong>YOU ACKNOWLEDGE THAT YOU HAVE READ THESE TERMS, UNDERSTAND THE TERMS, AND WILL BE BOUND BY THESE TERMS. YOU FURTHER ACKNOWLEDGE THAT THESE TERMS TOGETHER WITH THE PRIVACY POLICY REPRESENT THE COMPLETE AND EXCLUSIVE STATEMENT OF THE AGREEMENT BETWEEN US AND THAT IT SUPERSEDES ANY PROPOSAL OR PRIOR AGREEMENT ORAL OR WRITTEN, AND ANY OTHER COMMUNICATIONS BETWEEN US RELATING TO THE SUBJECT MATTER OF THIS AGREEMENT.</strong></p> 
        </section>
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 
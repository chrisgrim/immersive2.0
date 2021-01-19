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
        <div class="account-notifications_header">
            <h2>Privacy Policy</h2>
        </div>
        <section class="account-index-content">
            <p><strong>Effective Date: 17-08-2020</strong></p>
            <p>We are committed to protecting Your privacy. This Privacy Policy describes how We collect, use, store, share and protect the information collected through https://everythingimmersive.com/ (the “<strong>Website</strong>”).<br> In this Privacy Policy, "We", "Us", and "Our" refer to Everything Immersive. For more information about Us, see “Our Details” at the end of this Policy.</p>
            <p>This Privacy Policy is incorporated into and subject to Our <strong>Terms and Conditions</strong>. Capitalised words used but not defined in this Privacy Policy have the meaning given to them in the Terms and Conditions.</p>
            <p>Please be advised that the practices described in this Privacy Policy apply only to information gathered online through the Website. This Privacy Policy applies where We are acting as a data controller with respect to the Personal Data of visitors, users, clients and customers (“You,” or “Your”); in other words, where We determine the purposes and means of the processing of that Personal Data.</p>
            <p>PLEASE READ THIS PRIVACY POLICY CAREFULLY because it affects Your rights under the law. You confirm that You have read and agreed to be bound by this Privacy Policy. If You do not agree with this Privacy Policy, You cannot use, access, create or publish in the Website. This Privacy Policy may change as We continuously improve the Website, so please check it periodically.</p>
            <p>We reserve the right to modify or amend the Privacy Policy from time to time without notice. Your continued use of the Website following the posting of changes to these terms will mean You accept those changes. If We intend to apply the modifications or amendments to this Privacy Policy retroactively or to Personal Data already in Our possession, We will provide You with notice of the modifications or amendments.</p>
            <p>Our Website incorporates privacy controls which affect how We will process Your Personal Data. By using the privacy controls, You can specify whether You would like to receive direct marketing communications and limit the publication of Your information. You can access the privacy controls via URL</p><h2 id="tableofcontents">Contents</h2>
            <p>Click below to jump to any section of this privacy policy</p>
            <ol type="1">
            <li><a href="#nonpersonal"><strong>Your Data</strong></a>
            </li>
             <li><a href="#personaldata"><strong>Personal Data</strong></a>
             <ol>
             <li><a href="#personaldata-1">Data We Collect</a></li>
             <li><a href="#personaldata-2">How We Collect Data</a></li>
             </ol>
            </li>
             <li><a href="#personaldatasocial"><strong>Personal Data On Or From Social Media</strong></a>
            </li>
             <li><a href="#personaldatause"><strong>Use of Personal Data</strong></a>
            </li>
             <li><a href="#datashare"><strong>Sharing Of Personal Data</strong></a>
            </li>
             <li><a href="#dataretention"><strong>Retaining and Deleting Personal Data</strong></a>
            </li>
             <li><a href="#datasecurity"><strong>Data Security</strong></a>
            </li>
             <li><a href="#nonpersonaldata"><strong>Non-Personal Data</strong></a>
             <ol>
             <li><a href="#nonpersonaldata-1">Generally, We collect and store the following Non-Personal Data:</a></li>
             </ol>
            </li>
             <li><a href="#nonpersonaldatause"><strong>Use of Non-Personal Data</strong></a>
            </li>
             <li><a href="#nonpersonalfromother"><strong>Personal &amp; Non-Personal Data From Other Sources</strong></a>
            </li>
             <li><a href="#accountdata"><strong>Account Data</strong></a>
            </li>
             <li><a href="#usercontentdata"><strong>User Content Data</strong></a>
            </li>
             <li><a href="#enquirydata"><strong>Enquiry Data</strong></a>
            </li>
             <li><a href="#csrdata"><strong>Customer Relationship Data</strong></a>
            </li>
             <li><a href="#transactionsdata"><strong>Transactions And Payment Data</strong></a>
            </li>
             <li><a href="#notificationsdata"><strong>Notifications Data</strong></a>
            </li>
             <li><a href="#correspondencedata"><strong>Correspondence Data</strong></a>
            </li>
             <li><a href="#combinationdata"><strong>Combination Of Data</strong></a>
            </li>
             <li><a href="#gdprrights"><strong>Your Data Privacy Rights</strong></a>
            </li>
             <li><a href="#gdprprotection"><strong>Data Protection</strong></a>
            </li>
             <li><a href="#gdprtransfers"><strong>International Transfer Of The Personal Data of EU Citizens</strong></a>
            </li>
             <li><a href="#yourchoices"><strong>Your Choices</strong></a>
            </li>
             <li><a href="#access"><strong>Your Right to Access</strong></a>
            </li>
             <li><a href="#withdraw"><strong>Your Right to Withdraw Consent</strong></a>
            </li>
             <li><a href="#CUD"><strong>Your Right to Update, Correct or Delete</strong></a>
            </li>
             <li><a href="#caloppa"><strong>California Privacy Rights</strong></a>
            </li>
             <li><a href="#nochildren"><strong>No Personal Data From Children</strong></a>
            </li>
             <li><a href="#cookieinformation"><strong>Cookie Policy</strong></a>
             <ol>
             <li><a href="#cookieinformation-1">Cookie We Use</a></li>
             <li><a href="#cookieinformation-2">Purpose Of Using Cookies</a></li>
             <li><a href="#cookieinformation-3">Cookies Used by Our Service Providers</a></li>
             <li><a href="#cookieinformation-4">Managing Cookies</a></li>
             </ol>
            </li>
             <li><a href="#hyperlinks"><strong>Links To Or From Another Website</strong></a>
            </li>
             <li><a href="#publicarea"><strong>Public Areas</strong></a>
            </li>
             <li><a href="#changesandupdates"><strong>Changes And Updates</strong></a>
            </li>
             <li><a href="#assignment"><strong>Assignment</strong></a>
            </li>
             <li><a href="#contacttable"><strong>Our Details</strong></a>
            </li>

            </ol>
            <h2 id="nonpersonal">1 Your Data</h2><p>Everything Immersive strives to provide you with the best possible service. In order to provide this service we may need to collect information from you from time to time. At all times we try to only collect the information we need for the particular function or activity we are carrying out and use it in accordance with this privacy policy.</p>
            <p>We collect two basic types of information from You from Your use of the Website: Personal Data and Non-Personal Data.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="personaldata">2 Personal Data</h2>
            <p>"<strong>Personal Data</strong>" means any information relating to an identified or identifiable natural person; an identifiable natural person is one who can be identified, directly or indirectly, in particular by reference to an identifier such as a name, an identification number, location data, an online identifier or to one or more factors specific to the physical, physiological, genetic, mental, economic, cultural or social identity of that natural person.</p>
            <p>As a general matter, You can browse the Website without submitting Your Personal Data to Us. However, there are a number of circumstances in which You may supply Us with Your Personal Data.</p>
            <h3 id="personaldata-1">2.1 Data We Collect</h3>
            <p>We collect the following Personal Data from You:</p>
            <ul>
            <li>Full Name;</li>
            <li>E-mail;</li>
            <li>Residential Address;</li>
            <li>Mailing Address;</li>
            <li>Your Digital Photograph;</li>

            </ul>
            <h3 id="personaldata-2">2.2 How We Collect Data</h3>
            <p><strong>The following are the most common ways in which You give Your Personal Data:</strong></p>
            <ul>
            <li>Participating in communities, chat rooms and comment threads, other fora, and other interactive services in the Website;</li>
            <li>Submission of User Content on any part of the Website that permit it;</li>
            <li>Any other place in the Website where You knowingly volunteer to give Personal Data.</li>
            <li>Registration for an Account on the Website;</li>
            <li>Registration for a Subscription;</li>
            <li>Signing up to receive e-mail newsletters or e-mail alerts from Us;</li>
            <li>Signing up to receive e-mail offers from Us or Our partners;</li>
            <li>Requesting for service or other assistance;</li>
            <li>Participating in surveys, panels, or market research in the Website;</li>
            <li>Participating in special events and promotions in the Website;</li>
            <li>Entering in contests or sweepstakes in the Website;</li>

            </ul>


            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="personaldatasocial">3 Personal Data On Or From Social Media</h2>
            <p>When You interact with the Website or Your Account through a social media platform, such as Facebook, Twitter, Tumblr, LinkedIn, YouTube, or Pinterest, We may collect the Personal Data that You make available to Us on that page including Your account ID or handle. We will comply with the privacy policies of the corresponding social media platform and We will only collect and store such Personal Data that We are permitted to collect by these social media platforms.</p>
            <p>Please do NOT supply any other person's Personal Data to Us, unless We prompt You to do so.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="personaldatause">4 Use of Personal Data</h2>
            <p>We do NOT sell or license Your Personal Data to any third party.</p>
            <p>We may process any of Your Personal Data identified in this Privacy Policy where necessary for the establishment, exercise or defence of legal claims, whether in court proceedings or in an administrative or out-of-court procedure. The legal basis for this processing is Our legitimate interests, namely the protection and assertion of Our legal rights, Your legal rights and the legal rights of others.</p>
            <p>In addition to the specific purposes for which We may process Your Personal Data set out in this clause, We may also process any of Your Personal Data where such processing is necessary for compliance with a legal obligation to which We are subject, or in order to protect Your vital interests or the vital interests of another natural person.</p>
            <p>Specifically, We use Your Personal Data for the following:</p>
            <ul>
            <li>responding to Your queries and requests;</li>
            <li>investigating complaints;</li>
            <li>enforcing Our Terms and Conditions;</li>
            <li>as otherwise required or authorised by law or government agency.</li>
            <li>providing Goods and Services to You;</li>
            <li>improving and developing Our Website;</li>
            <li>processing Your requests or instructions;</li>
            <li>customising Services;</li>
            <li>rewards, competitions, marketing or other promotional materials;</li>
            <li>recognising You when Your return to Our website;</li>
            <li>providing tips, news and/or updates;</li>
            <li>processing Your feedback on Goods and Services;</li>
            <li>creating personalised offers;</li>

            </ul>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="datashare">5 Sharing Of Personal Data</h2>
            <p>We may share your Personal Data with the following:</p>
            <p><strong>Our Group of Companies</strong>. We may disclose Your <strong>Personal Data</strong> to any member of Our group of companies, which means Our subsidiaries, Our ultimate holding company and all its subsidiaries insofar as reasonably necessary for the purposes, and on the legal bases, set out in this Privacy Policy. Information about Our group of companies can be found at http://noproscenium.com/.</p>


            <p><strong>Third-Party Suppliers</strong>. We may disclose Your <strong>Enquiry Data</strong> to one or more of those selected third-party suppliers of Goods and Services identified on Our Website for the purpose of enabling them to contact You so that they can offer, market and sell relevant Goods and/or Services to You. Each such third party will act as a data controller in relation to the Enquiry Data that We supply to it; and upon contacting You, each such third party will supply to You a copy of its own privacy policy, which will govern that third party's use of Your Personal Data. The suppliers and providers with whom We may share Your information include:</p>
            <ul>
            <li>Social networking sites</li>
            <li>Analytics services</li>
            <li>IT service providers and web hosting companies</li>
            <li>Market researchers</li>
            <li>Legal, auditing and other professional advisors or consultants</li>
            <li>Billing or data storage services</li>

            </ul>
            <p><strong>Other Purposes</strong>. In addition to the specific disclosures of Personal Data set out in this Section, We may disclose Your <strong>Personal Data</strong> where such disclosure is necessary for compliance with a legal obligation to which We are subject, or in order to protect Our vital interests, Your vital interests, or the vital interests of another natural person. We may also disclose Your Personal Data where such disclosure is necessary for the establishment, exercise or defence of legal claims, whether in court proceedings or in an administrative or out-of-court procedure.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="dataretention">6 Retaining and Deleting Personal Data</h2>
            <p>Personal Data that We process for any purpose shall not be kept for longer than is necessary for that purpose or those purposes.</p>
            <p>In some cases, it is not possible for Us to specify in advance the periods for which Your Personal Data will be retained. In such cases, We will determine the period of retention based on our legitimate interests, namely the proper administration of Our Website and business.</p>
            <p>Notwithstanding the other provisions of this Section, We may retain Your Personal Data where such retention is necessary for compliance with a legal obligation to which We are subject, or in order to protect Your vital interests or the vital interests of another natural person.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="datasecurity">7 Data Security</h2>
            <p>We follow generally accepted industry standards to protect the information submitted to Us, both during transmission and once We receive it. We maintain appropriate administrative, technical and physical safeguards to protect Personal Data against accidental or unlawful destruction, accidental loss, unauthorised alteration, unauthorised disclosure or access, misuse, and any other unlawful form of processing of the Personal Data in Our possession. This includes, for example, firewalls, password protection and other access and authentication controls.</p>
            <p>We use <strong>HTTPS</strong></p>
            <p>HTTPS (Hypertext Transfer Protocol Secure) is an internet communication protocol that protects the integrity and confidentiality of data between your computer and everythingimmersive.com/. The use of HTTPS makes sure your communication with us is:</p>
            <p><strong>Encrypted</strong> — The data you exchange with us is secure from eavesdroppers. That means that when you are browsing everythingimmersive.com/, nobody can track your activities across multiple pages, or steal the data exchange between your computer and everythingimmersive.com/.</p>
            <p><strong>Integral</strong> — The use of HTTPS preserves the integrity of data. Your data cannot be modified or corrupted during transfer.</p>
            <p><strong>Authenticated</strong> — HTTPS protocol authenticates your communication with us. This ensures that you are always communicating with our servers.</p>
            <p>However, no method of transmission over the Internet, or method of electronic storage, is 100% secure. We cannot ensure or warrant the security of any information You transmit to Us or store on the Website, and You do so at Your own risk. We also cannot guarantee that such information may not be accessed, disclosed, altered, or destroyed by breach of any of Our physical, technical, or managerial safeguards. If You believe Your Personal Data has been compromised, please contact Us at contact@everythingimmersive.com.</p>
            <p>If We learn of a security systems breach, then We attempt to notify You electronically so that You can take appropriate protective steps. We may post a notice through the Website if a security breach occurs.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="nonpersonaldata">8 Non-Personal Data</h2>
            <p>Non-Personal Data includes information that does not personally identify You, but may include tracking and usage information about Your location, demographics, use of the Website and the internet.</p>
            <p>When You interact with the Website, We may collect Non-Personal Data and Our servers may automatically keep an activity log of Your use of the Website.</p>
            <h3 id="nonpersonaldata-1">8.1 Generally, We collect and store the following Non-Personal Data:</h3>
            <ul>
            <li>Demographic information such as age, gender, and five-digit zip code as part of collecting Personal Data;</li>
            <li>Device information about Your computer, mobile device, or other device that You use to access the Website. This information may include IP address, geolocation information, unique device identifiers, browser type, browser language, and other transactional information;</li>
            <li>Usage information about Your use of the Website. This information includes a reading history of the pages You view.</li>
            <li>Additional “traffic information” such as time of access, date of access, software crash reports, application errors, session identification number, access times, and referring site addresses.</li>
            <li>Your search terms and search results.</li>
            <li>Other information regarding Your use of the Website.</li>

            </ul>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="nonpersonaldatause">9 Use of Non-Personal Data</h2>
            <p>We may disclose or share Non-Personal Data (or other information, other than Personal Data) in any other manner that We deem appropriate or necessary.</p>
            <p>Among other things, We will disclose Non-Personal Data to third parties to help Us determine how users use parts of the Website and who Our users are so We can improve the Website.</p>
            <p>We will also disclose Non-Personal Data to Our partners and other third parties about how Our users collectively use the Website.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="nonpersonalfromother">10 Personal &amp; Non-Personal Data From Other Sources</h2>
            <p>We may also collect and store information about You that We receive from other sources to, among other things, enable Us to update and correct the information contained in Our database and to better customise Your experience on the Website.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="accountdata">11 Account Data</h2>
            <p>We may process Your Account Data ("<strong>Account Data</strong>"). The Account Data may include Your name and email address. The source of the Account Data is You. The Account Data may be processed for the purposes of operating Our Website, providing Our Services, ensuring the security of Our Website and Services, maintaining back-ups of Our databases and communicating with You.
            The legal basis for this processing is our legitimate interests, namely the proper administration of Our Website and business.
            </p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="usercontentdata">12 User Content Data</h2>
            <p>We may process information that You post for publication on Our Website or through Our Services ("<strong>User Content Data</strong>"). The User Content Data may be processed for the purposes of enabling such publication and administering Our Website and Services.
            The legal basis for this processing is our legitimate interests, namely the proper administration of Our Website and business.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="enquirydata">13 Enquiry Data</h2>
            <p>We may process information contained in any enquiry You submit to Us regarding Our Goods and/or Services ("<strong>Enquiry Data</strong>"). The Enquiry Data may be processed for the purposes of offering, marketing and selling relevant Goods and/or Services to You.
            The legal basis for this processing is consent.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="csrdata">14 Customer Relationship Data</h2>
            <p>We may process information relating to Our customer relationships, including customer contact information ("Customer Relationship Data"). The Customer Relationship Data may include Your name, Your contact details, and information contained in communications between You and Us. You are the source of the Customer Relationship Data. The Customer Relationship Data may be processed for the purposes of managing Our relationships with customers, communicating with customers, keeping records of those communications and promoting Our Goods and Services to customers. The legal basis for this processing is Our legitimate interests, namely the proper management of Our customer relationships.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="transactionsdata">15 Transactions And Payment Data</h2>
            <p>We may process information relating to transactions, including purchases of Goods and Services, that You enter into with Us and/or through Our Website (“<strong>Transaction Data</strong>”). The Transaction Data may include Your contact details, Your card details, the transaction details, and/or your authentication details. The Transaction Data may be processed for the purpose of supplying the purchased Goods and Services and keeping proper records of those transactions.
            The legal basis for this processing is our legitimate interests, namely the proper administration of Our Website and business and a contract between user and you or taking steps at user request to enter a contract.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="notificationsdata">16 Notifications Data</h2>
            <p>We may process information that You provide to Us for the purpose of subscribing to Our email notifications and/or newsletters ("<strong>Notification Data</strong>"). The Notification Data may be processed for the purposes of sending You the relevant notifications and/or newsletters.
            The legal basis for this processing is consent.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="correspondencedata">17 Correspondence Data</h2>
            <p>We may process information contained in or relating to any communication that You send to Us ("<strong>Correspondence Data</strong>"). The Correspondence Data may include the communication content and metadata associated with the communication. Our Website will generate the metadata associated with communications made using the Website contact forms. The Correspondence Data may be processed for the purposes of communicating with You and record-keeping.
            The legal basis for this processing is Our legitimate interests, namely the proper administration of Our Website and business and communications with users.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="combinationdata">18 Combination Of Data</h2>
            <p>We use the information from different portions of the Website, all of which are owned and operated by Us, and We may combine information gathered from multiple portions of the Website into a single user record. We also use and/or combine information that We collect or receive from third party sources to enhance, expand, and check the accuracy of Your user records.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="gdprrights">19 Your Data Privacy Rights</h2>
            <p>If You are a citizen of the EU, We have summarised the rights that You have under the General Data Protection Regulation (GDPR) in this Section. Some of the rights are complex, and not all of the details have been included in Our summaries. Accordingly, You should read the relevant laws and guidance from the regulatory authorities for a full explanation of these rights.</p>
            <p><strong>Right to Access</strong>. You have the right to confirmation as to whether or not We process Your Personal Data and, where We do, access to the Personal Data, together with certain additional information. That additional information includes details of the purposes of the processing, the categories of Personal Data concerned and the recipients of the Personal Data. Providing the rights and freedoms of others are not affected, We will supply to You a copy of Your Personal Data. The first copy will be provided free of charge, but additional copies may be subject to a reasonable fee. You can access Your Personal Data by visiting <a href="https://everythingimmersive.com/contact">https://everythingimmersive.com/contact</a> when logged into Our Website.</p>
            <p><strong>Right to Rectification.</strong> You have the right to have any inaccurate Personal Data about You rectified and, taking into account the purposes of the processing, to have any incomplete Personal Data about You completed.</p>
            <p><strong>Right to Erasure.</strong> In some circumstances You have the right to the erasure of Your Personal Data without undue delay. Those circumstances include: the Personal Data are no longer necessary in relation to the purposes for which they were collected or otherwise processed; You withdraw consent to consent-based processing; You object to the processing under certain rules of applicable data protection law; the processing is for direct marketing purposes; and the Personal Data have been unlawfully processed.</p>
            <p>However, there are exclusions of the right to erasure. The general exclusions include where processing is necessary: for exercising the right of freedom of expression and information; for compliance with a legal obligation; or for the establishment, exercise or defence of legal claims.</p>
            <p><strong>Right to Restrict Processing</strong>. In some circumstances You have the right to restrict the processing of Your Personal Data. Those circumstances are: You contest the accuracy of the Personal Data; processing is unlawful but You oppose erasure; We no longer need the Personal Data for the purposes of Our processing, but You require Personal Data for the establishment, exercise or defence of legal claims; and You have objected to processing, pending the verification of that objection. Where processing has been restricted on this basis, We may continue to store Your Personal Data. However, We will only otherwise process it: with Your consent; for the establishment, exercise or defence of legal claims; for the protection of the rights of another natural or legal person; or for reasons of important public interest.</p>
            <p><strong>Right to Object to Processing</strong>. You have the right to object to Our processing of Your Personal Data on grounds relating to Your particular situation, but only to the extent that the legal basis for the processing is that the processing is necessary for: the performance of a task carried out in the public interest or in the exercise of any official authority vested in Us; or the purposes of the legitimate interests pursued by Us or by a third party. If You make such an objection, We will cease to process the Personal Data unless We can demonstrate compelling legitimate grounds for the processing which override Your interests, rights and freedoms, or the processing is for the establishment, exercise or defence of legal claims.</p>
            <p>You have the right to object to Our processing of Your Personal Data for direct marketing purposes (including profiling for direct marketing purposes). If You make such an objection, We will cease to process Your Personal Data for this purpose.</p>
            <p>You have the right to object to Our processing of Your Personal Data for scientific or historical research purposes or statistical purposes on grounds relating to Your particular situation, unless the processing is necessary for the performance of a task carried out for reasons of public interest.</p>
            <p><strong>Right to Data Portability</strong>. To the extent that the legal basis for Our processing of Your Personal Data is consent, or that the processing is necessary for the performance of a contract to which You are party or in order to take steps at Your request prior to entering into a contract, and such processing is carried out by automated means, You have the right to receive Your Personal Data from Us in a structured, commonly used and machine-readable format. However, this right does not apply where it would adversely affect the rights and freedoms of others.</p>
            <p><strong>Right to Complain to a Supervisory Authority</strong>. If You consider that Our processing of Your Personal Data infringes data protection laws, You have a legal right to lodge a complaint with a supervisory authority responsible for data protection. You may do so in the EU member state of Your habitual residence, Your place of work or the place of the alleged infringement.</p>
            <p><strong>Right to Withdraw Consent</strong>. To the extent that the legal basis for Our processing of Your Personal Data is consent, You have the right to withdraw that consent at any time. Withdrawal will not affect the lawfulness of processing before the withdrawal.</p>
            <p>You may exercise any of Your rights in relation to Your Personal Data through written notice to Us. See Our Contact Details at the end of this Policy.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="gdprprotection">20 Data Protection</h2>
            <p>If You are located in the European Economic Area, Your Personal Data will be processed by Everything Immersive. As part of providing the Website, this Personal Data may be transferred to other regions, including to Canada and the United States. Such transfers will be completed in compliance with relevant Data Protection Legislation.</p>
            <p>When Everything Immersive processes Personal Data in the course of providing the Website, Everything Immersive will:</p>
            <ul>
            <li>process the Personal Data as a Data Processor, only for the purpose of providing the Website in accordance with documented instructions from You (provided that such instructions are commensurate with the functionalities of the Website), and as may subsequently be agreed to by You. If Everything Immersive is required by law to Process the Personal Data for any other purpose, Everything Immersive will provide You with prior notice of this requirement, unless Everything Immersive is prohibited by law from providing such notice;</li>
            <li>notify You if, in Everything Immersive’s opinion, Your instruction for the processing of Personal Data infringes applicable Data Protection Legislation;</li>
            <li>notify You promptly, to the extent permitted by law, upon receiving an inquiry or complaint from You or a Supervisory Authority relating to Everything Immersive’s Processing of the Personal Data;</li>
            <li>implement and maintain appropriate technical and organisational measures to protect the Personal Data against unauthorised or unlawful processing and against accidental loss, destruction, damage, theft, alteration or disclosure. These measures shall be appropriate to the harm which might result from any unauthorised or unlawful processing, accidental loss, destruction, damage or theft of Personal Data and appropriate to the nature of the Personal Data which is to be protected;</li>
            <li>provide You, upon request, with up-to-date attestations, reports or extracts thereof where available from a source charged with auditing Everything Immersive’s data protection practices (e.g. external auditors, internal audit, data protection auditors), or suitable certifications, to enable You to assess compliance with the terms of this Privacy Policy;</li>
            <li>notify You promptly upon becoming aware of and confirming any accidental, unauthorised, or unlawful processing of, disclosure of, or access to the Personal Data;</li>
            <li>ensure that its personnel who access the Personal Data are subject to confidentiality obligations that restrict their ability to disclose the Personal Data; and</li>
            <li>upon termination of the Terms, Everything Immersive will promptly initiate its purge process to delete or anonymise the Personal Data. If You request a copy of such Personal Data within 60 days of termination, Everything Immersive will provide You with a copy of such Personal Data. </li>
            </ul>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="gdprtransfers">21 International Transfer Of The Personal Data of EU Citizens</h2>
            <p>In this Section, We provide information about the circumstances in which Personal Data of citizens of the European Union may be transferred to countries outside the European Economic Area (EEA).</p>
            <p>We have offices and facilities in United States.The European Commission has made an "adequacy decision" with respect to the data protection laws of United States. Transfers to United States will be protected by appropriate safeguards, namely the use of standard data protection clauses adopted or approved by the European Commission. A copy of the use of standard protection clauses adopted or approved by the European Commission can be obtained through: <a href="https://ec.europa.eu/info/law/law-topic/data-protection/international-dimension-data-protection/standard-contractual-clauses-scc_en">ec.europa.eu</a></p>
            <p>The hosting facilities for Our Website are situated in United States. The European Commission has made an "adequacy decision" with respect to the data protection laws of United States. Transfers to United States will be protected by appropriate safeguards, namely the use of standard data protection clauses adopted or approved by the European Commission. A copy of the use of standard protection clauses adopted or approved by the European Commission can be obtained through: <a href="https://ec.europa.eu/info/law/law-topic/data-protection/international-dimension-data-protection/standard-contractual-clauses-scc_en">ec.europa.eu</a></p>
            <p>You acknowledge that Personal Data that You submit for publication through Our Website or Services may be available, via the internet, around the world. We cannot prevent the use (or misuse) of such Personal Data by others.</p><p>
            The legal basis for this processing is Our legitimate interests, namely the proper administration of Our Website and business and communications with users.

            </p><p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="yourchoices">22 Your Choices</h2>
            <p>In general, if You register on the Website, You may update the information You have provided to Us.</p>
            <p>When You register on the Website, You may be able to indicate Your preferences regarding marketing communications. You may be able to change those preferences and select the categories of communications that interest You.</p>
            <p>You will not be able to opt-out of receiving certain service-related or transactional communications relating to the Website unless You cancel Your registration or subscription.</p>
            <p>Even after Your account is cancelled, We may retain Your information for the purposes set forth in this Privacy Policy.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="access">23 Your Right to Access</h2>
            <p>We provide You with reasonable access to the Personal Data that You may provide through the Website.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="withdraw">24 Your Right to Withdraw Consent</h2>
            <p>At any time, You may legitimately object to the processing of Your Personal Data, except if otherwise provided by applicable law. You may decline to share certain Personal Data with Us, in which case We may not be able to provide to You some of the features and functionality of the Website.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="CUD">25 Your Right to Update, Correct or Delete</h2>
            <p>You may update, correct, or delete Your Account Data and preferences at any time by accessing Your Account settings page on the Website. If You wish to access or amend any other Personal Data We hold about You, or to request that We delete any information about You that We have obtained from an Integrated Service, You may contact Us. Please note that while any changes You make will be reflected in active user databases instantly or within a reasonable period of time, We may retain all information You submit for backups, archiving, prevention of fraud and abuse, analytics, satisfaction of legal obligations, or where We otherwise reasonably believe that We have a legitimate reason to do so.</p>
            <p>We will however retain Your information needed for administrative and transactional communications.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="caloppa">26 California Privacy Rights</h2>
            <p>If You are a California resident, California Civil Code Section 1798.83 permits You to request and obtain from We, information regarding the disclosure of Your Personal Data to the third parties for direct marketing purposes in the preceding calendar year, free of charge, once a year.</p>
            <p>We do not share Your Personal Data with third parties for those parties' direct marketing use. For more information about Our privacy and data collection policies, You may wish to review Our Privacy Policy.</p>
            <p>California residents, who are under 18 and are registered users of We, are allowed to request and have removed, any content or information that they have posted publicly. However, in cases where the law does not require or allow the removal of information, this may not be applicable. This is under California Business and Professions Code Section 22581.</p>
            <p><strong>California Tracking Disclosure:</strong> We track the personally identifying information of Our users and visitors over time and across third party websites to provide targeted advertising. We respond to Do Not Track (DNT) signals. We act when:</p>
            <p>Our Website is operated from California, or</p>
            <p>Our users may be consumers residing in California.</p>
            <p>Some other third-party websites may also keep track of Your browsing activities so that they can tailor the information or advertising they present to You. If You wish to opt out of this tracking, You can enable privacy settings in Your browser.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="nochildren">27 No Personal Data From Children</h2>
            <p>We do not knowingly collect Personal Data from children under 13 years of age in the USA or 16 years of age in the European Union. The Website is not directed to children under the age of 13 in the USA or 16 in the European Union. We will not knowingly allow children under 13 in the USA or 16 in the European Union to register for or become users of the Website.</p>
            <p>If You are under 13 in the USA or 16 in the European Union years of age, You should not provide Personal Data to Us.</p>
            <p>If We discover that a child under the age of 13 in the USA or 16 in the European Union has provided Us with Personal Data and We do not have parental consent, We will immediately delete that child’s information.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="cookieinformation">28 Cookie Policy</h2>
            <p>A cookie is a file containing an identifier (a string of letters and numbers) that is sent by a web server to a web browser and is stored by the browser. The identifier is then sent back to the server each time the browser requests a page from the server.</p>
            <p>Cookies may be either "persistent" cookies or "session" cookies: a persistent cookie will be stored by a web browser and will remain valid until its set expiry date, unless deleted by the user before the expiry date; a session cookie, on the other hand, will expire at the end of the user session, when the web browser is closed.</p>
            <p>Cookies do not typically contain any information that personally identifies a user, but Personal Data that We store about You may be linked to the information stored in and obtained from cookies.</p>
            <h3 id="cookieinformation-1">28.1 Cookie We Use</h3><h4>Session Cookies</h4>
            <p>These are temporary cookies that contain no expiration date and&nbsp; are deleted after the users browser is closed. You will be required to log in again at the next session, where you will be treated as a new user</p>
            <p><strong>Session cookies examples:</strong> Shopping cart, remembering previous actions, managing and passing security tokens, multimedia content player session cookies, load balancing session cookies and third party social content plug in sharing cookies</p>

            <h3 id="cookieinformation-2">28.2 Purpose Of Using Cookies</h3><p><strong>Authentication</strong> - We use cookies to identify You when You visit Our Website and as You navigate Our Website;</p>
            <p><strong>Status</strong> - We use cookies to help Us determine if You are logged into Our Website;</p>
            <p><strong>Personalisation</strong> - We use cookies to store information about Your preferences and to personalise the Website for You;</p>
            <p><strong>Security</strong> - We use cookies as an element of the security measures used to protect user accounts, including preventing fraudulent use of login credentials, and to protect Our Website and Services generally;</p>
            <p><strong>Analysis</strong> - We use cookies to help Us to analyse the use and performance of Our Website and Services;</p>

            <h3 id="cookieinformation-3">28.3 Cookies Used by Our Service Providers</h3>
            <p>Our service providers use cookies and those cookies may be stored on Your computer when You visit Our Website.</p>
            <p><strong>Google Analytics</strong>. We use Google Analytics to analyse the use of Our Website. Google Analytics gathers information about Website use by means of cookies. The information gathered relating to Our Website is used to create reports about the use of Our Website. Google's privacy policy is available at: <a href="https://www.google.com/policies/privacy/">https://www.google.com/policies/privacy/</a>.</p>

            <h3 id="cookieinformation-4">28.4 Managing Cookies</h3>
            <p>You can disable cookie tracking by visiting the following link: <a target="_blank" href="https://optout.aboutads.info/?c=2&amp;lang=EN">https://optout.aboutads.info/?c=2&amp;lang=EN</a></p>


            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="hyperlinks">29 Links To Or From Another Website</h2>
            <p>The Website may contain links to other sites operated by Our affiliates or third parties. Please be advised that the practices described in this Privacy Policy do not apply to information gathered through these other sites. We are not responsible for the actions and privacy policies of third parties and other sites.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="publicarea">30 Public Areas</h2>
            <p>Please remember that any information You share in public areas, such as message boards or feedback sections, becomes public and anyone may take and use that information. Please be careful about what You disclose and do not post any Personal Data that You expect to keep private. Please consult the applicable guidelines, if any, for use of Our chat rooms and other community areas for more information.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="changesandupdates">31 Changes And Updates</h2>
            <p>Please revisit this page periodically to stay aware of any changes to this Privacy Policy, which We may update from time to time. If We modify the Privacy Policy, We will make it available through the Website, and indicate the date of the latest revision. Any material changes will be posted on this Website and will come into effect 30 Days after their posting.</p>
            <p>In the event that the modifications materially alter Your rights or obligations hereunder, We will make reasonable efforts to notify You of the change. For example, We may send a message to Your email address, if We have one on file, or generate a pop-up or similar notification when You access the Website for the first time after such material changes are made. If You do not refuse the changes in writing before they become effective, this shall mean that You have consented to the Privacy Policy as changed. Your continued use of the Website after the revised Privacy Policy has become effective indicates that You have read, understood and agreed to the current version of the Privacy Policy.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="assignment">32 Assignment</h2>
            <p>We may change Our ownership or corporate organisation while providing the Website and Services. We may also sell certain assets associated with the Website. As a result, please be aware that in such event, We may transfer some or all of Your information to a company acquiring all or part of Our assets or to another company with which We have merged. Under such circumstances We would, to the extent possible, require the acquiring party to follow the practices described in this Privacy Policy, as it may be amended from time to time. Nevertheless, We cannot promise that an acquiring company or the merged company will have the same privacy practices or treat Your information the same as described in this Privacy Policy.</p>

            <p><a href="#tableofcontents">Back to table of contents</a></p><h2 id="contacttable">33 Our Details</h2><p>If you have questions or concerns regarding this policy or if you need to make a request please contact us at:</p>
            <table style="width: 100%">
            <colgroup>
            <col span="1" style="width: 30%;">
            <col span="1" style="width: 70%;">
            </colgroup>
            <tbody><tr>
            <td><strong>E-mail:</strong></td>
            <td>contact@everythingimmersive.com</td></tr>
            </tbody>
            </table>

            <p><a href="#tableofcontents">Back to table of contents</a></p> 
        </section>
    </div>
@endsection

@section('footer')
    <vue-footer></vue-footer>
@endsection 
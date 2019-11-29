<?php
class Messages{
public $reqFullName = "Please enter your full name.";

public $reqFirstName = "Please enter your full name.";

public $reqLastName = "Please enter your full name.";
public $reqPhoneNumber = "Please enter your valid phone number.";
public $reqPassword = "Please enter your password (min 6 characters).";
public $reqField = "Required, Please fill this field.";
public $reqAdvCity = "Please specify city for you ads to appear";
public $reqConfirmPassword = "";
public $reqUsername = "Please enter your username";
public $duplicatEmail = "Email id already exist in system. Please try another one.";
public $duplicateAccount = "Account already exist in system.";
public $reqEmail = "Please enter your valid email address.";
public $reqCity = "Required: Invalid city name";
public $reqCountry = "Required: Invalid country name";
public $reqState = "Required: Invalid state/provice name";
public $reqPostCode = "Required: Invalid Postal Code";
public $reqInstitute = "Required: Invalid institute name";
public $reqAddr = "Required: Invalid address";
public $reqTitle = "Required: Please enter the title";
public $reqFile = "Required: Please attach a file";
public $reqKeywords = "Required: Please enter keywords";
public $reqAuthors = "Required: Please enter the author(s) name(s)";
public $reqCreditCard = "Please enter your credit card information";
public $reqCcv = "Please enter 3 digit ccv number from the back of your credit card";
public $reqQuestion = "Please enter question.";
public $reqAnswer = "Please fill this answer field";

//form notifications
public $adPostSuccess = "Ad posted successfully";
public $msgDonateSuccess = "Thank you for you donation";
public $msgFileUploadSuccess = "File uploaded Successfully";
public $msgFileUploadFial = "File Uploaded Fail";


public $fileUploadFailed = "File uploading failed";
public $invalidFile = "Invalid File";
public $registrationSuccess = "Account created successfully.";
public $registrationFailed = "Account creation failed";
public $userUnavailable = "Username not available. Please try another one.";
public $editProfileSuccess = "Profile updated successfully";
public $editProfileFailed = "Sorry but the system was unable to save your profile.";
public $accountNotExist = "Account does not exist";
public $generalActionError = "Sorry, but the system was unable to perform the requested action.";
public $reqEmailVerification = "Email verification is required. Please follow the link we sent on your email id.";
public $accountSuspended = "Your account is suspended";
public $emailSendingFail = "Sorry, but the system was unable to send mail. Please try again later.";
public $passwordRecoveryMailSuccessMsg = "New password has been emailed to you.";
public $invalidLoginDetails = "Invalid details. Please try again.";
public $manuscriptSubmissionSuccessfull = "Manuscript submitted successfully";
public $noInfo = "No information found";
public $noReviewerExist = "No reviewer account found in the system";
public $ajaxDelSuccess = "Record deleted. Please refresh the page to view changes";
public $delError = "Error in deleting file.";

public $mailMsgAssign = "A manuscript has been assigned to you for reviewing.<br>Please login to<a href='#'>your account</a>to check manuscript";
public $mailSubAssign = "A manuscript is assigned to you for reviewing";
public $revinfoApproveTitle = "Confirm the approval so this manuscript becomes available for author to download";

public $checklistMsg1 = "One author designated as corresponding author";
public $checklistMsg2 = "Telephone and fax numbers, and E-mail address of the corresponding author";
public $checklistMsg3 = "Running title";
public $checklistMsg4 = "Key words";
public $checklistMsg5 = "Page and line numbers";
public $checklistMsg6 = "All tables (including title, description, footnotes) and figures (separated from figure legends) are provided in a single file with main text for initial submission";
public $checklistMsg7 = "References are in the correct format for this journal";
public $checklistMsg8 = "All references mentioned in the Reference list are cited in the text, and vice versa";
public $checklistMsg9 = "Names of three potential Reviewers with complete address including E-mail";
public $checklistMsg10 = "Originality certificate duly signed by all the authors";

public $msgInsertSuccess = "Record inserted successfully.";
public $msgInsertFail = "Sorry, but the system was unable to save data. Please try again later";
public $msgActionSuccess="Action completed successfully.";
public $msgUpdateSuccess="Record update successfull.";
public $msgUpdateFail="Record update fail. Please try again later.";
public $msgDeleteSuccess = "Record deleted successfully";
public $msgActionFail = "Sorry, but the system was unable to perform required action.";
public $msgError = "error.";

public $uploadrevReqFeedback = "Required, Please enter your feedback";


public $reqManuscriptTitle = "Required, Please enter the title";
public $reqManuscriptAbstract = "Required, Please enter the Abstract";
public $reqManuscriptKeywords = "Required, Please enter the keywords";
public $reqCoverLetter = "Required, Please enter the cover letter";
public $coverLetterInstructions = "<div id='inst'>Please enter a cover letter here.<br>
You should include any of the following items that are appropriate for your paper:<br>
<ul><li>The name of the suggested reviewer(s).</li>
<li>Any comments or suggestions that may be useful to the editor(s).</li>
<li>Your rebuttal to the previous reviews (resubmission only).</li></ul>
<br>
<b>IMPORTANT! </b><br>
<ul><li>The cover letter should be entered only here.</li>
<li>If you have included the cover letter in any of the files you are about to submit,</li>
<li>please remove it from those files before proceeding with this submission.</li></ul></div>";



public $reviewerNoPendingManuscript = "No manuscript pending for review";
public $loginAccountPending = "Your account has not active yet";
public $loginAccountSuspended = "Your account has been suspended";



}




<?php

return [
    'login' => 'Login',
    'logout' => 'Logout',
    'register' => 'Register',
    'create_poll' => 'Create poll',
    'forgot_password' => 'Forgot Your Password?',
    'remember' => 'Remember me',
    'email' => 'Email',
    'password' => 'Password',
    'avatar' => 'Avatar',
    'confirm_password' => 'Confirm password',
    'name' => 'Name',
    'label_gender' => 'Gender',
    'male' => 'Male',
    'female' => 'Female',
    'profile' => 'Profile',
    'edit' => 'Edit',
    'home' => 'Home',
    'admin_page' => 'ADMIN PAGE',
    'errors' => 'Error',

    /**
     * MASTER ADMIN
     */
    'placeholder_search' => 'START TYPING...',
    'name_admin_page' => 'ADMIN - VOTING',
    'main_menu' => 'MAIN MENU',
    'footer' => 'Copyright &copy; 2016',
    'nav_menu' => [
        'user' => 'User',
        'poll' => 'Poll',
    ],

    /**
     * EMAIL
     */
    'mail' => [
        'head' => 'POLL - VOTING',
        'link_vote' => 'Link to vote:',
        'link_admin' => 'Link manager vote:',
        'subject' => 'Voting',
        'edit_link' => 'You edited link of poll successfully, New link: ',
        'open_poll' => 'Admin of poll reopened poll',
        'close_poll' => 'Admin closed poll',
        'delete_all_participant' => 'Admin of poll deleted all participant',
        'register_active_mail' => 'You register successfully. Please click on link to active account. Link here: ',
        'edit_poll' => [
            'head' => 'Voting - Change',
            'summary' => 'Poll of you changed!',
            'thead' => [
                'STT' => 'NO.',
                'info' => 'INFORMATION',
                'old_data' => 'OLD DATA',
                'new_data' => 'NEW DATA',
                'date' => 'DATE',
            ],
        ],
        'create_poll' => [
            'title' => 'Poll',
            'head' => 'POLL - VOTE',
            'dear' => 'Dear',
            'thank' => 'Thank you because you have used website our. <br> Your poll created SUCCESS. Below, it\'s two link which they send your mail.',
            'link_vote' => 'Link to vote for this poll',
            'description_link_vote' => 'Send this link to every body that you want invite for participant.',
            'link_admin' => 'Link to administrator for this poll',
            'description_link_admin' => 'Access this link help you can change, close or delete poll of you.',
            'password' => 'Password',
            'note' => '*<u>Note</u>: You can login our website without must not register a new account. Let\'s click "Active account" to open account of you',
            'active_account' => 'Active account',
            'end' => '-- END --',
        ],
        'participant_vote' => [

        ],
        'edit_option' => [
            'old_option' => 'OLD OPTION',
            'new_option' => 'NEW OPTION',
            'thank' => 'Thank you because you have used website our',
            'title' => 'Change option',
        ],
    ],
    'paginations' => 'Showing :start to :finish of :numberOfRecords entry|Showing :start to :finish of :numberOfRecords entries',
    'gender' => [
        '' => '',
        '0' => 'Female',
        '1' => 'Male',
        '2' => 'Other',
    ],
];

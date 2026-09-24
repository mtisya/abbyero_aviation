        <div class='row'>
            <div class='col-md-6 col-sm-12' id='organizationAccordion'>
                <div class='card'>
                    <div class='card-header' id='organizationHeading'>
                        <h5 class='mb-0'> <button class='btn accordion-header-btn btn-link text-start w-100'
                                aria-controls='organizationCollapse' aria-expanded='true' aria-label='Toggle navigation'
                                data-bs-target='#organizationCollapse' data-bs-toggle='collapse' type='button'>
                                Instructor <span class='fas accordion-toggle-icon fa-chevron-right'></span> </button>
                        </h5>
                    </div>
                    <div class='collapse show account-card-div' id='organizationCollapse'
                        aria-labelledby='organizationHeading' data-parent='#organizationAccordion'>
                        <div class='row'>
                            <div class='col-12 mb-3 form-floating' id='organization_div'>
                                <p id='instructor_p'> You can add your instructor's email address here. Adding an
                                    instructor will allow them to monitor your course progress. </p> <input
                                    id='cfi_email_address' type='email' class='form-control'>
                                <div id='added_instructor'></div>
                            </div>
                            <div id='student-invitations'
                                style='margin-bottom:10px;display:flex;flex-direction:column;gap:5px'></div>
                            <div class='col-12 mb-3'> <button class='btn btn-primary' id='save-instructor'
                                    style='display:none'> Add Instructor </button> <button class='btn btn-primary'
                                    id='remove-instructor' style='display:none'> Remove Instructor </button> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-md-6 col-sm-12' id='flight-school-row'>
                <div class='card'>
                    <div class='card-header'>
                        <h5 class='mb-0'> <button class='btn accordion-header-btn btn-link text-start w-100'
                                aria-controls='flightSchoolCollapse' aria-expanded='true' aria-label='Toggle navigation'
                                data-bs-target='#flightSchoolCollapse' data-bs-toggle='collapse' type='button'> Flight
                                School Invitations <span class='fas accordion-toggle-icon fa-chevron-right'></span>
                            </button> </h5>
                    </div>
                    <div class='collapse show account-card-div' id='flightSchoolCollapse'>
                        <div class='row'>
                            <div class='col-12 mb-3 form-floating'>
                                <p id='instructor_p'> As an instructor, you have the ability to be invited to multiple
                                    flight schools. If you have been invited to a flight school, you can accept the
                                    invitation here. Any flight schools you are currently a part of will be seen in
                                    InstructorView. </p>
                            </div>
                            <div id='school-invitations'
                                style='margin-bottom:10px;display:flex;flex-direction:column;gap:5px'></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <dialog id='invite-modal' style='padding:30px;border:none;border-radius:25px'>
            <h3>Do you accept or reject this invitation?</h3>
            <p id='invite-statement'></p>
            <div class='row' style='justify-content:center'>
                <div class='row' style='width:fit-content;gap:5px'> <button class='btn btn-primary col'
                        id='accept-invite' onclick='acceptInvite()' style='width:fit-content'> Accept </button> <button
                        class='btn btn-primary col' id='reject-invite' onclick='rejectInvite()'
                        style='width:fit-content'> Reject </button> </div>
            </div>
        </dialog>
        <dialog id='attach-modal' style='padding:30px;border:none;border-radius:25px'>
            <h3><span id='instructor_name'></span> has multiple organizations.</h3>
            <h5>Select which to be attached to below</h5>
            <h6 class='small'> If you are not sure, please contact your instructor. </h6>
            <div class='row' style='justify-content:center'>
                <table class='table' id='multiple_org_table'>
                    <thead>
                        <tr>
                            <th scope='col'>Organization</th>
                            <th scope='col'></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table> <button class='btn btn-secondary' id='closeAttachModal'> Cancel </button>
            </div>
        </dialog>
        <div class='row'>
            <div class='col-md-6 col-sm-12' id='passwordAccordion'>
                <div class='card'>
                    <div class='card-header' id='passwordHeading'>
                        <h5 class='mb-0'> <button class='btn accordion-header-btn btn-link text-start w-100'
                                aria-controls='passwordCollapse' aria-expanded='true' aria-label='Toggle navigation'
                                data-bs-target='#passwordCollapse' data-bs-toggle='collapse' type='button'> Password
                                <span class='fas accordion-toggle-icon fa-chevron-right'></span> </button> </h5>
                    </div>
                    <div class='collapse show account-card-div' id='passwordCollapse' aria-labelledby='passwordHeading'
                        data-parent='#passwordAccordion'>
                        <p>You may change your password at any time.</p> <button class='btn btn-primary' id='contact'
                            onclick='window.location.href="https://account.atlantaflight.com/if/flow/dynamic-authentication-flow/?next=%2Fif%2Fflow%2Fpassword-change%2F"'>
                            Change on Account Services </button>
                    </div>
                </div>
            </div>
            <div class='col-md-6 col-sm-12' id='MFAAccordion'>
                <div class='card'>
                    <div class='card-header' id='MFAHeading'>
                        <h5 class='mb-0'> <button class='btn accordion-header-btn btn-link text-start w-100'
                                aria-controls='MFACollapse' aria-expanded='true' aria-label='Toggle navigation'
                                data-bs-target='#MFACollapse' data-bs-toggle='collapse' type='button'> Multi-Factor
                                Authentication Settings <span class='fas accordion-toggle-icon fa-chevron-right'></span>
                            </button> </h5>
                    </div>
                    <div class='collapse show account-card-div' id='MFACollapse' aria-labelledby='MFAHeading'
                        data-parent='#MFAAccordion'>
                        <div class='row'>
                            <div class='col-12 mb-3 form-floating'>
                                <div class='mb-3' id='configured_devices_div'>
                                    <p id='no_mfa_configured'> <strong>You have not configured any multi-factor
                                            authentication devices.</strong> </p>
                                    <p id='mfa_configured'> You have configured the following multi-factor
                                        authentication devices. Click on each to manage them. </p>
                                    <div class='w-100 align-items-center d-flex flex-row justify-content-between'
                                        id='configured_devices' style='gap:8px;padding-left:24px;padding-right:24px'>
                                        <span class='mfa-icon-link configured-mfa-icon'
                                            data-bs-content='View TOTP Devices' data-bs-placement='top'
                                            data-bs-toggle='popover' data-bs-trigger='hover focus' id='configured-totp'
                                            style='text-decoration:none;cursor:pointer'
                                            onclick='openMfaConfigModal("totp")'> <i
                                                class='fas fa-2x text-secondary fa-mobile-alt'></i> </span> <span
                                            class='mfa-icon-link configured-mfa-icon disabled'
                                            data-bs-content='SMS support coming soon' data-bs-placement='top'
                                            data-bs-toggle='popover' data-bs-trigger='hover focus' id='configured-sms'
                                            style='text-decoration:none;cursor:not-allowed;opacity:.5'
                                            aria-disabled='true' tabindex='-1'> <i
                                                class='fas fa-2x text-secondary fa-sms'></i> <span
                                                class='small ms-2 text-muted'>Coming soon</span> </span> <span
                                            class='mfa-icon-link configured-mfa-icon disabled'
                                            data-bs-content='Email support coming soon' data-bs-placement='top'
                                            data-bs-toggle='popover' data-bs-trigger='hover focus' id='configured-email'
                                            style='text-decoration:none;cursor:not-allowed;opacity:.5'
                                            aria-disabled='true' tabindex='-1'> <i
                                                class='fas fa-2x text-secondary fa-envelope'></i> <span
                                                class='small ms-2 text-muted'>Coming soon</span> </span> <span
                                            class='mfa-icon-link configured-mfa-icon'
                                            data-bs-content='View Static (Backup) Codes' data-bs-placement='top'
                                            data-bs-toggle='popover' data-bs-trigger='hover focus'
                                            id='configured-static' style='text-decoration:none;cursor:pointer'
                                            onclick='openMfaConfigModal("static")'> <i
                                                class='fas fa-2x text-secondary fa-key'></i> </span> <span
                                            class='mfa-icon-link configured-mfa-icon'
                                            data-bs-content='View WebAuthn (Security Keys)' data-bs-placement='top'
                                            data-bs-toggle='popover' data-bs-trigger='hover focus'
                                            id='configured-webAuthn' style='text-decoration:none;cursor:pointer'
                                            onclick='openMfaConfigModal("webauthn")'> <i
                                                class='fas fa-2x text-secondary fa-fingerprint'></i> </span> </div>
                                </div>
                                <div id='available_devices_div'>
                                    <p id='mfa-available-p'> You can configure one or more multi-factor authentication
                                        devices below. </p>
                                    <div class='w-100 align-items-center d-flex flex-row justify-content-between'
                                        id='available_devices' style='gap:8px;padding-left:24px;padding-right:24px'> <a
                                            href='https://account.atlantaflight.com/if/flow/dynamic-authentication-flow/?next=%2Fif%2Fflow%2Fauthenticator-totp-setup%2F'
                                            class='mfa-icon-link' data-bs-content='Configure TOTP'
                                            data-bs-placement='top' data-bs-toggle='popover'
                                            data-bs-trigger='hover focus' id='configure-totp'
                                            style='text-decoration:none;cursor:pointer'> <i
                                                class='fas fa-2x text-secondary fa-mobile-alt'></i> </a> <span
                                            class='mfa-icon-link disabled' data-bs-content='SMS support coming soon'
                                            data-bs-placement='top' data-bs-toggle='popover'
                                            data-bs-trigger='hover focus' id='configure-sms'
                                            style='text-decoration:none;cursor:not-allowed;opacity:.5'
                                            aria-disabled='true' tabindex='-1'> <i
                                                class='fas fa-2x text-secondary fa-sms'></i> <span
                                                class='small ms-2 text-muted'>Coming soon</span> </span> <span
                                            class='mfa-icon-link disabled' data-bs-content='Email support coming soon'
                                            data-bs-placement='top' data-bs-toggle='popover'
                                            data-bs-trigger='hover focus' id='configure-email'
                                            style='text-decoration:none;cursor:not-allowed;opacity:.5'
                                            aria-disabled='true' tabindex='-1'> <i
                                                class='fas fa-2x text-secondary fa-envelope'></i> <span
                                                class='small ms-2 text-muted'>Coming soon</span> </span> <a
                                            href='https://account.atlantaflight.com/if/flow/dynamic-authentication-flow/?next=%2Fif%2Fflow%2Fauthenticator-static-setup%2F'
                                            class='mfa-icon-link' data-bs-content='Configure Static (Backup) Codes'
                                            data-bs-placement='top' data-bs-toggle='popover'
                                            data-bs-trigger='hover focus' id='configure-static'
                                            style='text-decoration:none;cursor:pointer'> <i
                                                class='fas fa-2x text-secondary fa-key'></i> </a> <a
                                            href='https://account.atlantaflight.com/if/flow/dynamic-authentication-flow/?next=%2Fif%2Fflow%2Fauthenticator-webauthn-setup%2F'
                                            class='mfa-icon-link' data-bs-content='Configure WebAuthn (Security Key)'
                                            data-bs-placement='top' data-bs-toggle='popover'
                                            data-bs-trigger='hover focus' id='configure-webAuthn'
                                            style='text-decoration:none;cursor:pointer'> <i
                                                class='fas fa-2x text-secondary fa-fingerprint'></i> </a> </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='row'>
            <div class='col-md-6 col-xs-12' id='marketingEmailsAccordion'>
                <div class='card'>
                    <div class='card-header' id='marketingEmailsHeading'>
                        <h5 class='mb-0'> <button class='btn accordion-header-btn btn-link text-start w-100'
                                aria-controls='marketingEmailsCollapse' aria-expanded='true'
                                aria-label='Toggle navigation' data-bs-target='#marketingEmailsCollapse'
                                data-bs-toggle='collapse' type='button'> Marketing <span
                                    class='fas accordion-toggle-icon fa-chevron-right'></span> </button> </h5>
                    </div>
                    <div class='collapse show marketing-emails-div' id='marketingEmailsCollapse'
                        aria-labelledby='marketingEmailsHeading' data-parent='#marketingEmailsAccordion'>
                        <div class='row'>
                            <div class='col-12 form-floating mb-8'>
                                <p> If you do not wish to receive marketing emails, please click below. Saves your
                                    preference immediately. </p>
                            </div>
                            <div class='col-12 mb-3'> <label> <input id='marketingConsent' type='checkbox'
                                        onclick='setMarketingConsent()'> Unsubscribe from future marketing emails
                                </label> </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='col-md-6 col-sm-12' id='CookieAccordion'>
                <div class='card'>
                    <div class='card-header' id='CookieHeading'>
                        <h5 class='mb-0'> <button class='btn accordion-header-btn btn-link text-start w-100'
                                aria-controls='CookieCollapse' aria-expanded='true' aria-label='Toggle navigation'
                                data-bs-target='#CookieCollapse' data-bs-toggle='collapse' type='button'> Cookie Consent
                                <span class='fas accordion-toggle-icon fa-chevron-right'></span> </button> </h5>
                    </div>
                    <div class='collapse show account-card-div' id='CookieCollapse' aria-labelledby='CookieHeading'
                        data-parent='#CookieAccordion'>
                        <div class='row'>
                            <div class='col-12 mb-3 form-floating'>
                                <p> Manage your cookie consent settings. We use cookies to personalize content and to
                                    analyze our traffic. We will never sell customer information to any third-party
                                    service. This consent is browser specific. Saves your preference immediately. </p>
                                <input id='cookieConsent' type='checkbox' onclick='setCookieConsent()'> Allow
                                Third-Party Cookies
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class='row'>
            <div class='col-12' id='transactionHistoryAccordion'>
                <div class='card'>
                    <div class='card-header' id='transactionHistoryHeading'>
                        <h5 class='mb-0'> <button class='btn accordion-header-btn btn-link text-start w-100'
                                aria-controls='transactionHistoryCollapse' aria-expanded='true'
                                aria-label='Toggle navigation' data-bs-target='#transactionHistoryCollapse'
                                data-bs-toggle='collapse' type='button'> Transaction History <span
                                    class='fas accordion-toggle-icon fa-chevron-right'></span> </button> </h5>
                    </div>
                    <div class='collapse show account-card-div' id='transactionHistoryCollapse'
                        aria-labelledby='transactionHistoryHeading' data-parent='#transactionHistoryAccordion'>
                        <div class='row'>
                            <p> Below is your payment history. Click on any transaction below to download the receipt
                                for that transaction. </p>
                            <ul class='notice-list' id='payment_history_list'>
                                <li style='text-align:center'>No Payment History</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<{* Alumni Module - Profile Edit Form *}>
<{include file="db:alumni_header.tpl"}>

<div class="alumni-profile-edit-container">
    <div class="card">
        <div class="card-header">
            <h3 class="mb-0"><{$page_title}></h3>
        </div>
        <div class="card-body">
            <form method="post" action="profile.php" enctype="multipart/form-data" id="alumni-profile-form">
                <input type="hidden" name="op" value="save">
                <input type="hidden" name="XOOPS_TOKEN_REQUEST" value="<{$token}>">

                <!-- Personal Information -->
                <h4 class="mb-3"><{$smarty.const._MD_ALUMNI_PROFILE}></h4>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_FIRST_NAME}> <span class="text-danger">*</span></label>
                        <input type="text" name="first_name" class="form-control" value="<{$profile.first_name}>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_LAST_NAME}> <span class="text-danger">*</span></label>
                        <input type="text" name="last_name" class="form-control" value="<{$profile.last_name}>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_PROFILE_PHOTO}></label>
                    <{if $profile.photo}>
                        <div class="mb-2">
                            <img src="<{$profile.photo_url}>" alt="Current photo" class="img-thumbnail" style="max-width: 150px;">
                        </div>
                    <{/if}>
                    <input type="file" name="photo" class="form-control" accept="image/jpeg,image/png,image/gif">
                    <small class="form-text text-muted">Max 2MB. Formats: JPG, PNG, GIF</small>
                </div>

                <div class="mb-3">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_BIO}></label>
                    <textarea name="bio" class="form-control" rows="4"><{$profile.bio}></textarea>
                </div>

                <hr class="my-4">

                <!-- Academic Information -->
                <h4 class="mb-3">Academic Information</h4>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_GRADUATION_YEAR}> <span class="text-danger">*</span></label>
                        <select name="graduation_year" class="form-select" required>
                            <option value="">Select Year</option>
                            <{foreach item=year from=$years}>
                                <option value="<{$year}>" <{if $profile.graduation_year == $year}>selected<{/if}>><{$year}></option>
                            <{/foreach}>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_DEGREE}></label>
                        <input type="text" name="degree" class="form-control" value="<{$profile.degree}>">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_MAJOR}></label>
                        <input type="text" name="major" class="form-control" value="<{$profile.major}>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_DEPARTMENT}></label>
                        <input type="text" name="department" class="form-control" value="<{$profile.department}>">
                    </div>
                </div>

                <hr class="my-4">

                <!-- Professional Information -->
                <h4 class="mb-3">Professional Information</h4>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_JOB_TITLE}></label>
                        <input type="text" name="job_title" class="form-control" value="<{$profile.job_title}>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_COMPANY}></label>
                        <input type="text" name="company" class="form-control" value="<{$profile.company}>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_INDUSTRY}></label>
                    <select name="industry" class="form-select">
                        <option value="">Select Industry</option>
                        <{foreach key=key item=label from=$industries}>
                            <option value="<{$key}>" <{if $profile.industry == $key}>selected<{/if}>"><{$label}></option>
                        <{/foreach}>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_SKILLS}></label>
                    <input type="text" name="skills" class="form-control" value="<{$profile.skills_text}>" placeholder="e.g., PHP, JavaScript, Project Management">
                    <small class="form-text text-muted">Separate skills with commas</small>
                </div>

                <hr class="my-4">

                <!-- Location -->
                <h4 class="mb-3"><{$smarty.const._MD_ALUMNI_LOCATION}></h4>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_CITY}></label>
                        <input type="text" name="city" class="form-control" value="<{$profile.city}>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_STATE}></label>
                        <input type="text" name="state" class="form-control" value="<{$profile.state}>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_COUNTRY}></label>
                    <input type="text" name="country" class="form-control" value="<{$profile.country}>">
                </div>

                <hr class="my-4">

                <!-- Contact Information -->
                <h4 class="mb-3"><{$smarty.const._MD_ALUMNI_CONTACT}></h4>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_EMAIL}></label>
                        <input type="email" name="email" class="form-control" value="<{$profile.email}>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label"><{$smarty.const._MD_ALUMNI_PHONE}></label>
                        <input type="tel" name="phone" class="form-control" value="<{$profile.phone}>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_WEBSITE}></label>
                    <input type="url" name="website" class="form-control" value="<{$profile.website}>" placeholder="https://">
                </div>

                <hr class="my-4">

                <!-- Social Links -->
                <h4 class="mb-3"><{$smarty.const._MD_ALUMNI_SOCIAL_LINKS}></h4>

                <div class="mb-3">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_LINKEDIN_URL}></label>
                    <input type="url" name="linkedin" class="form-control" value="<{$profile.linkedin}>" placeholder="https://linkedin.com/in/yourprofile">
                </div>

                <div class="mb-3">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_TWITTER_HANDLE}></label>
                    <input type="text" name="twitter" class="form-control" value="<{$profile.twitter}>" placeholder="@yourhandle">
                </div>

                <div class="mb-3">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_FACEBOOK_URL}></label>
                    <input type="url" name="facebook" class="form-control" value="<{$profile.facebook}>" placeholder="https://facebook.com/yourprofile">
                </div>

                <hr class="my-4">

                <!-- Mentorship -->
                <h4 class="mb-3"><{$smarty.const._MD_ALUMNI_MENTORSHIP}></h4>

                <div class="mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="available_as_mentor" class="form-check-input" id="mentor-check" value="1" <{if $profile.available_as_mentor}>checked<{/if}>>
                        <label class="form-check-label" for="mentor-check">
                            <{$smarty.const._MD_ALUMNI_AVAILABLE_AS_MENTOR}>
                        </label>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Privacy -->
                <h4 class="mb-3"><{$smarty.const._MD_ALUMNI_PRIVACY_SETTINGS}></h4>

                <div class="mb-3">
                    <label class="form-label"><{$smarty.const._MD_ALUMNI_PRIVACY_LEVEL}></label>
                    <select name="privacy" class="form-select">
                        <option value="public" <{if $profile.privacy == 'public'}>selected<{/if}>><{$smarty.const._MD_ALUMNI_PRIVACY_PUBLIC}></option>
                        <option value="members" <{if $profile.privacy == 'members'}>selected<{/if}>><{$smarty.const._MD_ALUMNI_PRIVACY_MEMBERS}></option>
                        <option value="connections" <{if $profile.privacy == 'connections'}>selected<{/if}>><{$smarty.const._MD_ALUMNI_PRIVACY_CONNECTIONS}></option>
                        <option value="private" <{if $profile.privacy == 'private'}>selected<{/if}>><{$smarty.const._MD_ALUMNI_PRIVACY_PRIVATE}></option>
                    </select>
                </div>

                <!-- Submit Buttons -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<{$xoops_url}>/modules/alumni/profile.php?id=<{$profile.id}>" class="btn btn-secondary">
                        <{$smarty.const._MD_ALUMNI_CANCEL}>
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> <{$smarty.const._MD_ALUMNI_SAVE}>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<{include file="db:alumni_footer.tpl"}>

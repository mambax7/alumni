<{* Alumni Module - Recent Alumni Block *}>
<div class="alumni-block alumni-block-recent">
    <{if $block.profiles|@count > 0}>
        <div class="alumni-list">
            <{foreach item=profile from=$block.profiles}>
                <div class="alumni-item mb-3 pb-3 border-bottom">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <a href="<{$profile.url}>">
                                <img src="<{$profile.photo}>" alt="<{$profile.full_name}>" class="rounded-circle" style="width: 60px; height: 60px;">
                            </a>
                        </div>
                        <div class="col">
                            <h6 class="mb-1">
                                <a href="<{$profile.url}>"><{$profile.full_name}></a>
                            </h6>
                            <div class="small text-muted mb-1">
                                <i class="fa fa-graduation-cap"></i> Class of <{$profile.graduation_year}>
                            </div>
                            <{if $profile.job_title}>
                                <div class="small">
                                    <strong><{$profile.job_title}></strong>
                                    <{if $profile.company}> at <{$profile.company}><{/if}>
                                </div>
                            <{/if}>
                            <{if $profile.location}>
                                <div class="small text-muted">
                                    <i class="fa fa-map-marker-alt"></i> <{$profile.location}>
                                </div>
                            <{/if}>
                        </div>
                    </div>
                </div>
            <{/foreach}>
        </div>
        <div class="text-center mt-3">
            <a href="<{$xoops_url}>/modules/alumni/" class="btn btn-sm btn-primary">
                <{$smarty.const._MB_ALUMNI_VIEW_ALL}>
            </a>
        </div>
    <{else}>
        <p class="text-muted mb-0"><{$smarty.const._MB_ALUMNI_RECENT_EMPTY}></p>
    <{/if}>
</div>

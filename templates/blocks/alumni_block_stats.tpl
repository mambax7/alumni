<{* Alumni Module - Statistics Block *}>
<div class="alumni-block alumni-block-stats">
    <div class="stats-grid">
        <{if $block.show_profiles && isset($block.total_profiles)}>
            <div class="stat-item text-center mb-3">
                <div class="stat-icon mb-2">
                    <i class="fa fa-users fa-2x text-primary"></i>
                </div>
                <h4 class="stat-value mb-0"><{$block.total_profiles}></h4>
                <div class="stat-label small text-muted"><{$smarty.const._MD_ALUMNI_TOTAL_ALUMNI}></div>
            </div>
        <{/if}>

        <{if $block.show_events && isset($block.total_events)}>
            <div class="stat-item text-center mb-3">
                <div class="stat-icon mb-2">
                    <i class="fa fa-calendar fa-2x text-success"></i>
                </div>
                <h4 class="stat-value mb-0"><{$block.total_events}></h4>
                <div class="stat-label small text-muted"><{$smarty.const._MD_ALUMNI_EVENTS}></div>
                <{if isset($block.upcoming_events)}>
                    <div class="small text-muted">
                        (<{$block.upcoming_events}> <{$smarty.const._MD_ALUMNI_UPCOMING_EVENTS}>)
                    </div>
                <{/if}>
            </div>
        <{/if}>

        <{if $block.show_connections && isset($block.total_connections)}>
            <div class="stat-item text-center mb-3">
                <div class="stat-icon mb-2">
                    <i class="fa fa-user-friends fa-2x text-info"></i>
                </div>
                <h4 class="stat-value mb-0"><{$block.total_connections}></h4>
                <div class="stat-label small text-muted"><{$smarty.const._MD_ALUMNI_CONNECTIONS}></div>
            </div>
        <{/if}>

        <{if $block.show_mentorships && isset($block.total_mentorships)}>
            <div class="stat-item text-center mb-3">
                <div class="stat-icon mb-2">
                    <i class="fa fa-graduation-cap fa-2x text-warning"></i>
                </div>
                <h4 class="stat-value mb-0"><{$block.total_mentorships}></h4>
                <div class="stat-label small text-muted"><{$smarty.const._MD_ALUMNI_MENTORSHIP}></div>
            </div>
        <{/if}>

        <{if isset($block.countries)}>
            <div class="stat-item text-center mb-3">
                <div class="stat-icon mb-2">
                    <i class="fa fa-globe fa-2x text-danger"></i>
                </div>
                <h4 class="stat-value mb-0"><{$block.countries}></h4>
                <div class="stat-label small text-muted">Countries</div>
            </div>
        <{/if}>

        <{if isset($block.industries)}>
            <div class="stat-item text-center mb-3">
                <div class="stat-icon mb-2">
                    <i class="fa fa-briefcase fa-2x text-secondary"></i>
                </div>
                <h4 class="stat-value mb-0"><{$block.industries}></h4>
                <div class="stat-label small text-muted">Industries</div>
            </div>
        <{/if}>

        <{if isset($block.year_range)}>
            <div class="stat-item text-center mb-3">
                <div class="stat-icon mb-2">
                    <i class="fa fa-calendar-alt fa-2x text-primary"></i>
                </div>
                <div class="stat-value h6 mb-0"><{$block.year_range}></div>
                <div class="stat-label small text-muted">Alumni Years</div>
            </div>
        <{/if}>
    </div>
</div>

<style>
.alumni-block-stats .stat-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: transform 0.2s;
}
.alumni-block-stats .stat-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
.alumni-block-stats .stat-value {
    font-weight: bold;
    color: #333;
}
</style>

<{* Alumni Module - Search Block *}>
<div class="alumni-block alumni-block-search">
    <form method="get" action="<{$block.search_url}>" class="alumni-search-form">
        <!-- Keyword Search -->
        <div class="mb-3">
            <input type="text" name="q" class="form-control form-control-sm" placeholder="<{$smarty.const._MD_ALUMNI_SEARCH_PLACEHOLDER}>">
        </div>

        <!-- Graduation Year -->
        <div class="mb-3">
            <label class="form-label small"><{$smarty.const._MD_ALUMNI_GRADUATION_YEAR}></label>
            <select name="year" class="form-select form-select-sm">
                <option value=""><{$smarty.const._MD_ALUMNI_ALL}></option>
                <{section name=y start=$block.max_year loop=$block.min_year step=-1}>
                    <option value="<{$smarty.section.y.index}>"><{$smarty.section.y.index}></option>
                <{/section}>
            </select>
        </div>

        <!-- Location -->
        <div class="mb-3">
            <label class="form-label small"><{$smarty.const._MD_ALUMNI_LOCATION}></label>
            <input type="text" name="loc" class="form-control form-control-sm" placeholder="<{$smarty.const._MD_ALUMNI_CITY}>">
        </div>

        <!-- Submit -->
        <div class="d-grid">
            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fa fa-search"></i> <{$smarty.const._MD_ALUMNI_SEARCH}>
            </button>
        </div>

        <!-- Advanced Search Link -->
        <div class="text-center mt-2">
            <a href="<{$block.module_url}>/search.php" class="small">
                <{$smarty.const._MD_ALUMNI_ADVANCED_SEARCH}>
            </a>
        </div>
    </form>
</div>

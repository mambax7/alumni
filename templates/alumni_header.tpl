<{* Alumni Module - Common Header *}>
<link rel="stylesheet" href="<{$xoops_url}>/modules/alumni/assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<div class="alumni-module">
    <{if isset($breadcrumbs) && $breadcrumbs|@count > 0}>
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <{foreach item=crumb from=$breadcrumbs}>
                    <{if $crumb.url}>
                        <li class="breadcrumb-item"><a href="<{$crumb.url}>"><{$crumb.title}></a></li>
                    <{else}>
                        <li class="breadcrumb-item active" aria-current="page"><{$crumb.title}></li>
                    <{/if}>
                <{/foreach}>
            </ol>
        </nav>
    <{/if}>

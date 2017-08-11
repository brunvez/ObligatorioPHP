{extends file="layouts/main.tpl"}

{block name="head"}
    <link rel="stylesheet" href="/css/properties.css">
    <script src="/js/properties/index.js"></script>
    <script src="/js/properties/properties_list.js"></script>
{/block}


{block name="body"}
    {foreach from=$properties item="property"}
        {include file="partials/properties/property.tpl" property=$property}
    {/foreach}
{/block}
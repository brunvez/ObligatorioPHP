{extends file="layouts/main.tpl"}

{block name="head"}
    <script src="/js/properties/properties_list.js"></script>
{/block}

{block name="body"}
    <h1>Latest 30 properties</h1>
    {include file='partials/properties/properties_list.tpl' properties=$properties }

{/block}

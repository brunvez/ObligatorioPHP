{extends file="layouts/main.tpl"}

{block name="body"}
    <h1>Home</h1>
    {foreach from=$properties item=property}
        {include file='partials/properties/property.tpl' property=$property }
    {/foreach}
{/block}

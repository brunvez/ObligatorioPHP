<div class="row properties">
    {foreach from=$properties item=property key=index}
        {include file="partials/properties/property.tpl" property=$property}
    {/foreach}
</div>
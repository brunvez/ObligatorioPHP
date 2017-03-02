{extends file="layouts/main.tpl"}

{block name="head"}
    <script src="/js/cities/statistics.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
{/block}

{block name="body"}
    <div class="well">
        <label for="cities-select">Show statistics for </label>
        <select id="cities-select" class="form-control">
            <option value="" disabled selected>Select a City</option>
            {foreach from=$cities item=city}
                <option value="{$city->id()}">{$city->name}</option>
            {/foreach}
         </select>
    </div>
    <div id="container" style="width:100%; height:400px;"></div>
{/block}
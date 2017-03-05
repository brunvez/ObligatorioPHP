{extends file="layouts/main.tpl"}

{block name="head"}
    <link rel="stylesheet" href="/css/properties.css">
    <link rel="stylesheet" href="/css/jquery-ui/jquery-ui.css">
    <link rel="stylesheet" href="/css/jquery-ui/jquery-ui.structure.css">
    <link rel="stylesheet" href="/css/jquery-ui/jquery-ui.theme.css">
    <script src="/js/properties/index.js"></script>
    <script src="/js/properties/properties_list.js"></script>
    <script src="/js/galleria/galleria-1.5.3.js"></script>
    <script src="/js/jquery-ui/jquery-ui.js"></script>
{/block}

{block name="body"}
    <div class="row">
        <div class="{if empty($properties)}col-md-12{else}col-md-3{/if}">
            <div id="search-box" class="well well-lg" {if !empty($properties)}data-spy="affix" data-offset-top="0" {/if}>
                <form>
                    <div class="row">
                        <div>
                            <fieldset>
                                <legend>Search</legend>
                                <div class="form-group"><label for="operation-select">Operation*</label>
                                    <select name="operation" id="operation-select" required class="form-control">
                                        <option value="S"
                                                {if isset($smarty.get.operation) && $smarty.get.operation == 'S'}selected{/if}>
                                            For Sale
                                        </option>
                                        <option value="R"
                                                {if isset($smarty.get.operation) && $smarty.get.operation == 'R'}selected{/if}>
                                            Rental
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group"><label for="city-select">City* </label>
                                    <select name="city" id="city-select" required class="form-control">
                                        <option value="" selected disabled>Select a city</option>
                                        {foreach from=$cities item=city}
                                            <option value="{$city->id()}"
                                                    {if isset($smarty.get.city) && $smarty.get.city == $city->id()}
                                                        selected
                                                    {/if}
                                            >{$city->name}</option>
                                        {/foreach}
                                    </select>
                                </div>
                                <div class="form-group"><label for="neighborhood-select">Neighborhood</label>
                                    <select name="neighborhood" id="neighborhood-select" class="form-control">
                                        <option value="">Any</option>
                                        {if isset($neighborhoods)}
                                            {foreach from=$neighborhoods item=$neighborhood}
                                                <option value="{$neighborhood->id()}"
                                                        {if $smarty.get.neighborhood == $neighborhood->id()}selected{/if}
                                                >{$neighborhood->name}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="type-select">Type</label>
                                    <select name="type" id="type-select" class="form-control">
                                        <option value="" selected>Any</option>
                                        <option value="A"
                                                {if isset($smarty.get.type) && $smarty.get.type == 'A'}selected{/if}>
                                            Apartment
                                        </option>
                                        <option value="H"
                                                {if isset($smarty.get.type) && $smarty.get.type == 'H'}selected{/if}>
                                            House
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="rooms">Rooms</label>
                                    <input name="rooms" id="rooms" type="number" placeholder="Any"
                                           min="1"
                                            {if isset($smarty.get.rooms)} value="{$smarty.get.rooms}"{/if}
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Garage</label><br>

                                    <label for="garage-indifferent" class="checkbox-inline">
                                        <input type="radio" name="garage" value=""
                                               checked
                                               id="garage-indifferent"> Indifferent
                                    </label>

                                    <label for="garage-yes" class="checkbox-inline">
                                        <input type="radio" name="garage" value="1"
                                               {if isset($smarty.get.garage) && $smarty.get.garage}checked{/if}
                                               id="garage-yes"> Yes
                                    </label>

                                    <label for="garage-no" class="checkbox-inline">
                                        <input type="radio" name="garage" value="0" id="garage-no"
                                               {if isset($smarty.get.garage) && $smarty.get.garage === false}checked{/if}
                                        > No
                                    </label>
                                </div>
                                <div class="form-group">
                                    <p>
                                        <strong>Price between: </strong>
                                        <span id="amount"></span>
                                    </p>

                                    <div id="price-range"></div>

                                    <input type="number"
                                           value="{if isset($smarty.get.price_from)}{$smarty.get.price_from}{else}0{/if}"
                                           name="price_from"
                                           id="price-from-input" hidden>
                                    <input type="number"
                                           value="{if isset($smarty.get.price_to)}{$smarty.get.price_to}{else}500000{/if}"
                                           name="price_to"
                                           id="price-to-input" hidden>
                                </div>
                                <div class="form-group">
                                    <label for="order-select">Order By</label>
                                    <select class="form-control" name="order_by" id="order-select">
                                        <option value="" selected>Default</option>
                                        <option value="price"
                                                {if isset($smarty.get.order_by) && $smarty.get.order_by == 'price'}selected{/if}>
                                            Price
                                        </option>
                                        <option value="square_meters"
                                                {if isset($smarty.get.order_by) && $smarty.get.order_by == 'square_meters'}selected{/if}>
                                            Square Meters
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="order-select">Sort</label>
                                    <select class="form-control" name="direction" id="order-select">
                                        <option value="asc"
                                                {if isset($smarty.get.direction) && $smarty.get.direction == 'asc'}selected{/if}>
                                            Low to High
                                        </option>
                                        <option value="desc"
                                                {if isset($smarty.get.direction) && $smarty.get.direction == 'desc'}selected{/if}>
                                            High to Low
                                        </option>
                                    </select>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="row">
                        <button type="submit" class="btn btn-primary btn-block">Search</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-md-9">
            <div class="row">
                {if isset($error)}
                    <div class="alert alert-danger">
                        {$error}
                    </div>
                {/if}
            </div>
            {include file="partials/properties/properties_list.tpl" properties=$properties}
        </div>
    </div>
{/block}
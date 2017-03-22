{extends file="layouts/main.tpl"}

{block name="head"}
    <link rel="stylesheet" href="/css/properties.css">
    <script src="/js/properties/index.js"></script>
    <script src="/js/jquery-ui/jquery-ui.js"></script>
{/block}

{block name="body"}
    <div class="page-header">
        <h1>{$property->title|lower|ucfirst}
            <span class="pull-right">
                        <a class="btn btn-primary export-btn" href="/properties/{$property->id()}/generate_pdf"
                           target="_blank">
                            Export to PDF
                        </a>
            </span>
        </h1>
    </div>
    {if count($property->images()) > 0}
        <div id="property-photos" class="carousel slide" data-ride="carousel">

            <div class="carousel-inner" role="listbox">
                {foreach from=$property->images() item='image' key='index'}
                    <div class="item {if $index == 0}active{/if}">
                        <img src="{$image->url}">
                    </div>
                {/foreach}
            </div>

            <!-- Controls -->
            <a class="left carousel-control" href="#property-photos" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#property-photos" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div>
    {/if}

    {$neighborhood_average = $property->neighborhood()->average_price_per_square_meter()}
    {$property_average = $property->price_per_square_meter()}

    <h4 class="text-muted">{$property->type()} in
        {$property->neighborhood()->name}, {$property->neighborhood()->city()->name}</h4>
    <h5 class="text-muted">${$property->price}</h5>
    <h5 class="text-muted">{$property->square_meters} Square Meters</h5>
    <h5 class="text-muted">Average per Square Meter in Neighborhood: ${$neighborhood_average}</h5>
    <h5 class="text-muted">Price per Square Meter:
        {if $property_average > neighborhood_average}
            <span class="text-danger">
                            ${$property_average} (Above average)
                        </span>
        {else}
            <span class="text-success">
                            ${$property_average} (Below average)
                        </span>
        {/if}
    </h5>
    <h5 class="text-muted">{$property->rooms} {if $property->rooms == 1} Room{else} Rooms{/if},
        {$property->bathrooms} {if $property->bathrooms == 1} Bathroom {else} Bathrooms {/if}
    </h5>
    <h5 class="text-muted">Garage: {$property->has_garage()}</h5>
    <p>
        {$property->description}
    </p>
    <div id="questions">
        {foreach from=$property->questions() item='question'}
            {include file="partials/questions/question.tpl" question=$question}
        {/foreach}
    </div>
    <div>
        <div class="form-group">
            <label for="question-body">Ask a question, we are happy to answer!</label>
            <textarea id="question-body" cols="40" class="form-control"></textarea>
        </div>
        <div class="form-group">
            <button id="ask-question" class="btn btn-block btn-primary"
                    data-property-id="{$property->id()}">Ask
            </button>
        </div>
    </div>
{/block}

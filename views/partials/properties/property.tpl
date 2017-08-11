<div class="property col-md-6">
    <div class="thumbnail">
        {if $first_image = $property->first_image()}
            <img src="{$first_image->url}">
        {else}
            <img src="/img/house.jpg">
        {/if}
        <div class="caption">
            <h4><a href="/properties/{$property->id()}">{$property->title|lower|ucfirst}</a></h4>
            <h4 class="text-muted">{$property->type()} in
                {$property->neighborhood()->name}, {$property->neighborhood()->city()->name}</h4>
            <h5 class="text-muted">${$property->price}</h5>
            <h5 class="text-muted">{$property->square_meters} Square Meters</h5>
            <h5 class="text-muted">{$property->rooms} {if $property->rooms == 1} Room{else} Rooms{/if},
                {$property->bathrooms} {if $property->bathrooms == 1} Bathroom {else} Bathrooms {/if}
            </h5>
            <h5 class="text-muted">Garage: {$property->has_garage()}</h5>
            <p>{$property->description|truncate:150:'...':true}</p>
            <div class="button-group">
                <a href="/properties/{$property->id()}/modal"
                   class="btn btn-primary btn-block show-full-description" role="button">
                    Show Full Description
                </a>
            </div>
        </div>
    </div>
</div>
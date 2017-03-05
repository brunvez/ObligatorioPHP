<div class="property col-md-6">
    <div class="thumbnail">
        <img src="img/house.jpg">
        <div class="caption">
            <h4>{$property->title|lower|ucfirst}</h4>
            <h4 class="text-muted">{$property->type()} in
                {$property->neighborhood()->name}, {$property->neighborhood()->city()->name}</h4>
            <h5 class="text-muted">${$property->price}</h5>
            <h5 class="text-muted">{$property->square_meters} Square Meters</h5>
            <h5 class="text-muted">{$property->rooms} {if $property->rooms == 1} Room{else} Rooms{/if},
                {$property->bathrooms} {if $property->bathrooms == 1} Bathroom {else} Bathrooms {/if}
            </h5>
            <h5 class="text-muted">Garage: {if $property->garage} Yes {else} No {/if}</h5>
            <p>{$property->body|truncate:150:'...':true}</p>
            <div class="button-group">
                <a href="properties/{$property->id()}"
                   class="btn btn-primary btn-block show-full-description" role="button">
                    Show Full Description
                </a>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="property-title">Title</label>
    <input type="text" name="property[title]" id="property-title" placeholder="Title" class="form-control"
           value="{$property->title}">
</div>
<div class="row">
    <div class="form-group col-md-3">
        <label for="property-type">Type</label>
        <select name="property[type]" id="property-type" class="form-control">
            <option value="A" {if $property->type ==='A'}selected{/if}>Apartment</option>
            <option value="H" {if $property->type ==='H'}selected{/if}>House</option>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="property-operation">Operation</label>
        <select name="property[operation]" id="property-operation" class="form-control">
            <option value="S" {if $property->operation ==='S'}selected{/if}>For Sale</option>
            <option value="R" {if $property->operation ==='R'}selected{/if}>Rental</option>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="property-operation">Garage</label>
        <select name="property[garage]" id="property-operation" class="form-control">
            <option value="0" {if !$property->garage}selected{/if}>No</option>
            <option value="1" {if $property->garage}selected{/if}>Yes</option>
        </select>
    </div>
    <div class="form-group col-md-3">
        <label for="property-neighborhood">Neighborhood</label>
        <select name="property[neighborhood_id]" id="property-neighborhood" class="form-control">
            {foreach from=$neighborhoods item=neighborhood}
                <option value="{$neighborhood->id()}"
                        {if $property->neighborhood_id === $neighborhood->id()}selected{/if}>
                    {$neighborhood->name}
                </option>
            {/foreach}
        </select>
    </div>
</div>
<div class="row">
    <div class="form-group col-md-3">
        <label for="property-price">Price</label>
        <div class="input-group">
            <span class="input-group-addon">$</span>
            <input type="number" min="0" name="property[price]" value="{$property->price}" id="property-price"
                   class="form-control">
        </div>
    </div>
    <div class="form-group col-md-3">
        <label for="property-square-meters">Square Meters</label>
        <input type="number" min="1" name="property[square_meters]" value="{$property->square_meters}"
               id="property-square-meters"
               class="form-control">
    </div>
    <div class="form-group col-md-3">
        <label for="property-rooms">Rooms</label>
        <input type="number" min="1" name="property[rooms]" value="{$property->rooms}"
               id="property-rooms"
               class="form-control">
    </div>
    <div class="form-group col-md-3">
        <label for="property-bathrooms">Bathrooms</label>
        <input type="number" min="1" name="property[bathrooms]" value="{$property->bathrooms}"
               id="property-bathrooms"
               class="form-control">
    </div>
</div>
<div class="form-group">
    <label for="property-description">Description</label>
    <textarea name="property[description]" id="property-description" placeholder="Description" rows="10"
              class="form-control">{$property->description}</textarea>
</div>
{extends file="layouts/main.tpl"}

{block name="body"}
    <h1>Edit Property</h1>
    <form action="/properties/{$property->id()}/update" method="post">
        {include file="partials/properties/property_form.tpl" property=$property}
        <div class="row">
            <div class="col-md-6">
                <a type="submit" class="btn btn-block btn-danger" href="/properties/manage_properties">Cancel</a>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary btn-block">Edit</button>
            </div>
        </div>
    </form>
{/block}
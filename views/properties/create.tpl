{extends file="layouts/main.tpl"}

{block name="body"}
    <h1>New Property</h1>
    <form action="/properties/" method="post" enctype="multipart/form-data">
        {include file="partials/properties/property_form.tpl" property=$property}
        <div class="form-group">
            <label>Select some photos
                <input type="file" multiple name="photos[]">
            </label>
        </div>
        <div class="row">
            <div class="col-md-6">
                <a type="submit" class="btn btn-block btn-danger" href="/properties/manage_properties">Cancel</a>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary btn-block">Create</button>
            </div>
        </div>
    </form>
{/block}
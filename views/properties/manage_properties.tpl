{extends file="layouts/main.tpl"}

{block name="head"}
    <link rel="stylesheet" href="/css/manage_properties.css">
    <script src="/js/properties/manage_properties.js"></script>
{/block}

{block name="body"}
    <div class="well"><a href="#" class="btn btn-primary">Add Property</a></div>
    <div class="table-responsive">
        <table class="table table-hover table-striped">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Type</th>
                <th>Operation</th>
                <th>Neighborhood</th>
                <th>City</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            {foreach from=$properties item='property' key=index}
                <tr>
                    <td>{$property->id()}</td>
                    <td>{$property->title|lower|ucfirst}</td>
                    <td>{$property->type()}</td>
                    <td>{$property->operation()}</td>
                    <td>{$property->neighborhood()->name}</td>
                    <td>{$property->neighborhood()->city()->name}</td>
                    <td>
                        <a class='btn btn-primary btn-xs' href="/properties/{$property->id()}/edit">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                        <button class='btn btn-danger btn-xs delete-property' type="button"
                                data-property-id="{$property->id()}">
                            <span class="glyphicon glyphicon-trash"></span>
                        </button>
                    </td>
                </tr>
            {/foreach}
            </tbody>
        </table>
    </div>
    <nav>
        <ul class="pager">
            <li {if $first_page}class="disabled"{/if}>
                <a href="/properties/manage_properties?page={$previous_page}">Previous</a>
            </li>
            <li {if $last_page}class="disabled"{/if}>
                <a href="/properties/manage_properties?page={$next_page}">Next</a>
            </li>
        </ul>
    </nav>
    {*Confirm deletion modal*}
    <div class="modal fade" id="confirm" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Property</h4>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this property?
                    <div class="form-inline">
                        <div class="form-group">
                            <label for="dismiss-reason">Reason: </label>
                            <select id="dismiss-reason" class="form-control">
                                {foreach from=$dismiss_reasons item='dismiss_reason'}
                                    <option value="{$dismiss_reason->id()}">{$dismiss_reason->description}</option>
                                {/foreach}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger" id="delete">Delete</button>
                    <button type="button" data-dismiss="modal" class="btn">Cancel</button>
                </div>
            </div>
        </div>
    </div>
{/block}
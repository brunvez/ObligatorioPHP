<div class="modal fade" tabindex="-1" role="dialog" id="property-modal">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{$property->title|lower|ucfirst}</h4>
            </div>
            <div class="modal-body">
                <div class="galleria">
                    <img src="img/house.jpg">
                    <img src="img/house1.jpg">
                    <img src="img/house2.jpg">
                </div>
                <p>
                    {$property->body}
                </p>
            </div>
        </div>
    </div>
</div>

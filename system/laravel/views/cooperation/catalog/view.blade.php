<style type="text/css">
.cooperation-wrapper {
	background: white;
	padding: 20px;
	border-radius: 5px;
	margin-right: 20px;
}
.cooperation-image img{
	width: 100%;
}
</style>
<div class="box custom-sections section-product">
	<div class="box-heading box-sections box-block">
		<ul style="margin:0;">
			@foreach ($cooperations as $cooperation)
			<li><a href="{{ $opencart->url->link('account/cooperation', 'cooperation_id='.$cooperation->customer_group_id) }}">{{{ $cooperation->name }}}</a></li>
			@endforeach
</ul>
</div>
<div class="box-content">
	<div class="product-grid">
		@foreach ($customers as $customer)
		<div class="product-grid-item isotope-element section-0 section-1 xs-50 sm-33 md-33 lg-20 xl-20 display-both block-button isotope-item">
			<div class="cooperation-wrapper">
				<a href="{{ $opencart->url->link('account/register', 'promo='.$customer->promo) }}">
					<div class="cooperation-image"><img src="{{ $customer->custom_field_filepath(4)->filepath() }}"> </div>
					<div class="cooperation-name">{{{ $customer->firstname }}}{{{ $customer->lastname }}}</div>
				</a>
			</div>
		</div>
		@endforeach
	</div>
</div>

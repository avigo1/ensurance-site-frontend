<?php
/**
 * This template renders all lead modals for a given owner.
 *
 * You can override this template by copying it to your-theme-folder/geodir-pay-per-lead/admin-edit-lead-modal.php
 *
 * @package GeoDir_Pay_Per_Lead
 * @version 1.0.0
 *
 * @var array $lead The lead data.
 * @var string $currency_symbol The currency symbol.
 * @var string $currency_position The currency position.
 */

defined( 'ABSPATH' ) || exit;

use GeoDir_PPL\GeoDir_PPL_Lead;
?>
<div class="geodir-ppl-lead-edit">
	<form method="POST" class="geodir-ppl-edit-form" autocomplete="off">
		<input type="hidden" name="lead_id" value="<?php echo absint( $lead['id'] ); ?>">
		
		<div class="row">
			<div class="col-12 mb-1">
				<h6 class="fs-base mb-3 text-primary">
					<?php esc_html_e( 'Contact Information', 'geodir-ppl' ); ?>
				</h6>
				<div class="row">
					<div class="col-md-6">
						<?php
						aui()->input(
							array(
								'type'       => 'text',
								'id'         => 'lead-name',
								'name'       => 'name',
								'value'      => esc_attr( $lead['name'] ),
								'required'   => true,
								'label'      => __( 'Name', 'geodir-ppl' ),
								'label_type' => 'vertical',
								'label_show' => true,
								'size'       => 'md',
								'wrap_class' => 'mb-3',
							),
							true
						);
						?>
					</div>
				
					<div class="col-md-6">
						<?php
						aui()->input(
							array(
								'type'       => 'email',
								'id'         => 'lead-email',
								'name'       => 'email',
								'value'      => esc_attr( $lead['email'] ),
								'required'   => true,
								'label'      => __( 'Email', 'geodir-ppl' ),
								'label_type' => 'vertical',
								'label_show' => true,
								'size'       => 'md',
								'wrap_class' => 'mb-3',
							),
							true
						);
						?>
					</div>
				
					<div class="col-md-6">
						<?php
						aui()->input(
							array(
								'type'       => 'tel',
								'id'         => 'lead-phone',
								'name'       => 'phone',
								'value'      => esc_attr( $lead['phone'] ),
								'label'      => __( 'Phone', 'geodir-ppl' ),
								'label_type' => 'vertical',
								'label_show' => true,
								'size'       => 'md',
								'wrap_class' => 'mb-3',
							),
							true
						);
						?>
					</div> 
				</div>
			</div>

			<div class="col-12 mb-1">
				<h6 class="fs-base mb-3 text-primary">
					<?php esc_html_e( 'Pricing', 'geodir-ppl' ); ?>
				</h6>
				<div class="row">
					<?php if ( ! empty( $lead['budget'] ) ) : ?>
						<div class="col-md-6">
							<label for="lead-budget" class="form-label"><?php esc_html_e( 'Budget', 'geodir-ppl' ); ?></label>
							<div class="input-group mb-3">
								<?php if ( 'left' === $currency_position ) : ?>
									<span class="input-group-text"><?php echo esc_html( $currency_symbol ); ?></span>
								<?php endif; ?>

								<?php
								aui()->input(
									array(
										'type'       => 'number',
										'id'         => 'lead-budget',
										'name'       => 'budget',
										'value'      => esc_attr( $lead['budget'] ),
										'label_show' => false,
										'size'       => 'md',
										'no_wrap'    => true,
									),
									true
								);
								?>

								<?php if ( 'right' === $currency_position ) : ?>
									<span class="input-group-text"><?php echo esc_html( $currency_symbol ); ?></span>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>

					<div class="col-md-6">
						<label for="lead-price" class="form-label"><?php esc_html_e( 'Price', 'geodir-ppl' ); ?></label>
						<div class="input-group mb-3">
							<?php if ( 'left' === $currency_position ) : ?>
								<span class="input-group-text"><?php echo esc_html( $currency_symbol ); ?></span>
							<?php endif; ?>

							<?php
							aui()->input(
								array(
									'type'       => 'number',
									'id'         => 'lead-price',
									'name'       => 'price',
									'value'      => esc_attr( $lead['price'] ),
									'label_show' => false,
									'size'       => 'md',
									'no_wrap'    => true,
								),
								true
							);
							?>

							<?php if ( 'right' === $currency_position ) : ?>
								<span class="input-group-text"><?php echo esc_html( $currency_symbol ); ?></span>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="col-12 mb-1">
				<h6 class="fs-base mb-3 text-primary">
					<?php esc_html_e( 'Lead Details', 'geodir-ppl' ); ?>
				</h6>
				<div class="row">
					<div class="col-md-12">
						<?php
						aui()->input(
							array(
								'type'       => 'text',
								'id'         => 'lead-subject',
								'name'       => 'subject',
								'value'      => esc_attr( $lead['subject'] ),
								'required'   => true,
								'label'      => __( 'Subject', 'geodir-ppl' ),
								'label_type' => 'vertical',
								'label_show' => true,
								'size'       => 'md',
								'wrap_class' => 'mb-3',
							),
							true
						);
						?>
					</div>
					
					<div class="col-md-12">
						<?php
						aui()->textarea(
							array(
								'id'         => 'lead-message',
								'name'       => 'message',
								'value'      => esc_html( $lead['message'] ),
								'required'   => true,
								'label'      => __( 'Message', 'geodir-ppl' ),
								'label_type' => 'vertical',
								'label_show' => true,
								'rows'       => 5,
								'size'       => 'md',
								'wrap_class' => 'mb-3',
							),
							true
						);
						?>
					</div>
				</div>
			</div>
			
			<div class="col-md-6">
				<?php
				aui()->select(
					array(
						'id'         => 'lead-status',
						'name'       => 'status',
						'label'      => __( 'Status', 'geodir-ppl' ),
						'label_type' => 'vertical',
						'label_show' => true,
						'size'       => 'md',
						'select2'    => true,
						'wrap_class' => 'mb-3 w-100',
						'class'      => 'w-100 mw-100',
						'value'      => esc_attr( $lead['status'] ),
						'options'    => array(
							GeoDir_PPL_Lead::STATE_PENDING => __( 'Pending Payment', 'geodir-ppl' ),
							GeoDir_PPL_Lead::STATE_PENDING_APPROVAL => __( 'Pending Approval', 'geodir-ppl' ),
							GeoDir_PPL_Lead::STATE_APPROVED => __( 'Approved', 'geodir-ppl' ),
							GeoDir_PPL_Lead::STATE_REJECTED => __( 'Rejected', 'geodir-ppl' ),
							GeoDir_PPL_Lead::STATE_PURCHASED => __( 'Unlocked', 'geodir-ppl' ),
						),
					),
					true
				);
				?>
			</div>
		</div>

		<div class="alert alert-success d-none"></div>
		<div class="alert alert-danger d-none"></div>

		<div class="geodir-ppl-form-actions d-flex justify-content-between mt-3">
			<?php
			aui()->button(
				array(
					'type'             => 'button',
					'class'            => 'btn btn-secondary mr-2',
					'content'          => __( 'Cancel', 'geodir-ppl' ),
					'extra_attributes' => array( 'data-bs-dismiss' => 'modal' ),
				),
				true
			);

			aui()->button(
				array(
					'type'    => 'submit',
					'class'   => 'btn btn-primary',
					'content' => __( 'Save Changes', 'geodir-ppl' ),
				),
				true
			);
			?>
		</div>
	</form>
</div>

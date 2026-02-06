/**
 * External dependencies
 */
import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import {
	PanelBody,
	SelectControl,
	Disabled,
} from '@wordpress/components';
import { CheckboxControl } from '@woocommerce/blocks-checkout';
import './style.scss';

/**
 * Edit component for SMS consent block
 */
export const Edit = ( { attributes, setAttributes } ) => {
	const { text, smsDisclaimerText, smsStatus } = attributes;
	const blockProps = useBlockProps();

	const defaultText = __( 'Text me with news and offers', 'mailchimp-for-woocommerce' );
	const defaultDisclaimer = __( 'By providing your phone number, you agree to receive promotional and marketing messages, notifications, and customer service communications. Message & data rates may apply. Consent is not a condition of purchase. Message frequency may vary. You can unsubscribe at any time by replying STOP.', 'mailchimp-for-woocommerce' );

	return (
		<div { ...blockProps }>
			<InspectorControls>
				<PanelBody title={ __( 'SMS Consent Settings', 'mailchimp-for-woocommerce' ) }>
					<SelectControl
						label={ __( 'Default Checkbox State', 'mailchimp-for-woocommerce' ) }
						value={ smsStatus }
						options={ [
							{ label: __( 'Checked by default', 'mailchimp-for-woocommerce' ), value: 'check' },
							{ label: __( 'Unchecked by default', 'mailchimp-for-woocommerce' ), value: 'uncheck' },
						] }
						onChange={ ( value ) => setAttributes( { smsStatus: value } ) }
					/>
				</PanelBody>
			</InspectorControls>
			<Disabled>
				<div className="wc-block-components-checkout-step__container mailchimp-sms-consent">
					<div className="wc-block-components-checkout-step__content">
						<CheckboxControl
							id="subscribe-to-sms-preview"
							checked={ smsStatus === 'check' }
							onChange={ () => {} }
						>
							<span>{ text || defaultText }</span>
						</CheckboxControl>
						{ smsStatus !== 'hide' && (
							<div style={{ marginTop: '12px', marginLeft: '28px' }}>
								<div style={{ 
									padding: '8px 12px', 
									border: '1px solid #ccc', 
									borderRadius: '4px',
									color: '#757575',
									fontSize: '14px'
								}}>
									{ __( 'SMS Phone Number', 'mailchimp-for-woocommerce' ) }
								</div>
								<p style={{ fontSize: '12px', color: '#666', marginTop: '8px' }}>
									{ smsDisclaimerText || defaultDisclaimer }
								</p>
							</div>
						) }
					</div>
				</div>
			</Disabled>
		</div>
	);
};

export const Save = () => {
	return null;
};

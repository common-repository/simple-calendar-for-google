import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { SelectControl, TextControl, RangeControl, PanelBody } from '@wordpress/components';
import { InspectorControls, PanelColorSettings, useBlockProps } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	return (
		<div { ...blockProps }>
			<ServerSideRender
				block = 'simple-calendar-for-google/scgcalendar-block'
				attributes = { attributes }
			/>
			<SelectControl
				label = 'ID'
				value = { attributes.id }
				options = { scgcalendar_ids }
				onChange = { ( value ) => setAttributes( { id: value } ) }
			/>
			<InspectorControls>
				<SelectControl
					label = 'ID'
					value = { attributes.id }
					options = { scgcalendar_ids }
					onChange = { ( value ) => setAttributes( { id: value } ) }
				/>
				<PanelBody title = { __( 'Color', 'simple-calendar-for-google' ) } initialOpen = { false }>
					<PanelColorSettings
						title = { __( 'Text Color', 'simple-calendar-for-google' ) }
						colorSettings = { [
							{
								value: attributes.color,
								onChange: ( colorValue ) => setAttributes( { color: colorValue } ),
								label: __( 'Text Color', 'simple-calendar-for-google' ),
							}
						] }
					>
					</PanelColorSettings>
					<PanelColorSettings
						title = { __( 'Background Color', 'simple-calendar-for-google' ) }
						colorSettings = { [
							{
								value: attributes.bgcolor,
								onChange: ( colorValue ) => setAttributes( { bgcolor: colorValue } ),
								label: __( 'Background Color', 'simple-calendar-for-google' ),
							}
						] }
					>
					</PanelColorSettings>
				</PanelBody>
				<PanelBody title = { __( 'Schedule Duplication', 'simple-calendar-for-google' ) } initialOpen = { false }>
					<RangeControl
						label = { __( 'Upper limit of duplication', 'simple-calendar-for-google' ) }
						max = { 30 }
						min = { 1 }
						value = { attributes.duplimit }
						onChange = { ( value ) => setAttributes( { duplimit: value } ) }
					/>
					<PanelColorSettings
						title = { __( 'Background color when the upper limit of duplication is exceeded', 'simple-calendar-for-google' ) }
						colorSettings = { [
							{
								value: attributes.dupcolor,
								onChange: ( colorValue ) => setAttributes( { dupcolor: colorValue } ),
								label: __( 'Background Color', 'simple-calendar-for-google' ),
							}
						] }
					>
					</PanelColorSettings>
					<TextControl
						label = { __( 'Alternate text for [Show Appointment (Time Frame Only)]', 'simple-calendar-for-google' ) }
						value = { attributes.alttext }
						onChange = { ( value ) => setAttributes( { alttext: value } ) }
					/>
				</PanelBody>
			</InspectorControls>
		</div>
	);
}

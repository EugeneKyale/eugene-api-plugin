import { registerBlockType } from '@wordpress/blocks';
import { useEffect, useState } from '@wordpress/element';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { InspectorControls } from '@wordpress/block-editor';

registerBlockType('eugene/api-data-block', {
    title: 'API Data Block',
    category: 'eugene-api-plugin',
    attributes: {
        showId: { type: 'boolean', default: true },
        showFirstName: { type: 'boolean', default: true },
        showLastName: { type: 'boolean', default: true },
        showEmail: { type: 'boolean', default: true },
        showDate: { type: 'boolean', default: true }
    },
    edit: ({ attributes, setAttributes }) => {
        const [ data, setData ] = useState( null );
    
        useEffect(() => {
            wp.apiFetch({ path: '/eugene/v1/data' })
                .then( response => {
                    if ( response && response.data && response.data.headers && response.data.rows ) {
                        setData( response.data );
                    } else {
                        console.error( 'Unexpected API response format:', response );
                    }
                })
                .catch( error => {
                    console.error( 'Error fetching data', error );
                });
        }, []);

        // Show a loading message if data is not available yet.
        if ( ! data || ! data.headers || ! data.rows ) {
            return <p> Loading or no data available... </p>;
        }
    
        // Define the headers for the table.
        const headers = [
            {
                key: 'id',
                label: 'ID'
            },
            {
                key: 'fname',
                label: 'First Name'
            },
            {
                key: 'lname',
                label: 'Last Name'
            },
            {
                key: 'email',
                label: 'Email'
            },
            {
                key: 'date',
                label: 'Date'
            }
        ];
    
        return (
            <>
                <InspectorControls>
                    <PanelBody title="Column Visibility" initialOpen={ true }>
                        { headers.map( header => (
                            <ToggleControl
                                key={ header.key }
                                label={ `Show ${ header.label }` }
                                checked={ attributes[`show${ header.label.replace(/\s/g, '') }`] }
                                onChange={ () => setAttributes({ [`show${ header.label.replace(/\s/g, '') }`]: ! attributes[`show${ header.label.replace(/\s/g, '') }`] })}
                            />
                        ))}
                    </PanelBody>
                </InspectorControls>
                <div>
                    <h4>
                        { data.title }
                    </h4>
                    <table>
                        <thead>
                        <tr>
                            { headers.map(header => (
                                attributes[`show${ header.label.replace(/\s/g, '') }`] && <th key={ header.key }> { header.label } </th>
                            ))}
                        </tr>
                        </thead>
                        <tbody>
                        { Object.values( data.rows ).map(( row, index ) => (
                            <tr key={ index }>
                                { headers.map( header => (
                                    attributes[`show${ header.label.replace(/\s/g, '') }`] && <td key={ header.key }> { header.key === 'date' ? new Date( row[header.key] * 1000 ).toLocaleDateString() : row[header.key] } </td>
                                ))}
                            </tr>
                        ))}
                        </tbody>
                    </table>
                </div>
            </>
        );
    },    
    save: () => null, // Dynamic blocks handle saving in PHP.
});

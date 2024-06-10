import {registerBlockType} from '@wordpress/blocks';
import {useSelect} from '@wordpress/data';
import {useEffect, useState} from '@wordpress/element';

registerBlockType('eugene/api-data-block', {
    title: 'API Data Block',
    category: 'widgets',
    edit: () => {
        const [data, setData] = useState(null);

        useEffect(() => {
            wp.apiFetch({path: '/wp-json/eugene/v1/data'}).then(setData);
        }, []);

        if (!data) {
            return <p>Loading...</p>;
        }

        return (
            <div>
                <h4>{data.title}</h4>
                <table>
                    <thead>
                    <tr>
                        {data.headers.map(header => <th key={header}>{header}</th>)}
                    </tr>
                    </thead>
                    <tbody>
                    {data.rows.map((row, index) => (
                        <tr key={index}>
                            {Object.values(row).map((value, i) => <td key={i}>{value}</td>)}
                        </tr>
                    ))}
                    </tbody>
                </table>
            </div>
        );
    },
    save: () => null, // Dynamic blocks handle saving in PHP.
});

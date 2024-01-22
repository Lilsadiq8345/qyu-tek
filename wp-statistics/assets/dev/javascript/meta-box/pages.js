wps_js.pages_meta_box = {

    view: function (args = []) {
        let t = '';
        t += `<div class="o-table-wrapper"><table width="100%" class="o-table o-table--pages o-table--layout-fixed"><tbody>
        <tr>
            <th class="row-id"></th>
            <th colspan="6">${wps_js._('title')}</th>
            <th width="10%">${wps_js._('visits')}</th>
        </tr>`;

        const siteUrl = wps_js.global.admin_url.replace('/wp-admin/', '');
        let i = 1;
        args.pages.forEach(function (value) {
            t += `<tr>
			<td class="row-id">${i}</td>
			<td colspan="6"><a href="${siteUrl}${value['str_url']}" title="${value['title']}" class='wps-text-wrap' target="_blank">${value['title']} <span class="dashicons dashicons-external" style="font-size: 15px; vertical-align: middle"></span></a></td>
		    <td><a style="justify-content: flex-end;" href="${value['hits_page']}">${value['number']} 
		    <svg style="margin-top: 3px;" width="10" height="10" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M4.82751 4.99993 2.62209 2.79451c-.0759-.07859-.1179-.18384-.11695-.29309.00095-.10924.04477-.21375.12203-.291.07725-.07726.18176-.12108.291-.12203.10925-.00094.2145.04105.29309.11695l2.5 2.5c.07811.07814.12199.1841.12199.29459 0 .11048-.04388.21644-.12199.29458l-2.5 2.5c-.07859.0759-.18384.1179-.29309.11695-.10924-.00095-.21375-.04477-.291-.12203-.07726-.07725-.12108-.18176-.12203-.291-.00095-.10925.04105-.2145.11695-.29309l2.20542-2.20541Z" fill="#404BF2" fill-opacity=".5"/>
          <path d="M7.87792 5.13371 5.67251 2.9283c-.0759-.07859-.1179-.18384-.11695-.29309.00095-.10924.04477-.21375.12202-.291.07726-.07726.18176-.12108.29101-.12203.10925-.00095.2145.04105.29308.11695l2.5 2.5c.07812.07814.122.1841.122.29458 0 .11049-.04388.21645-.122.29459l-2.5 2.5c-.07858.0759-.18383.11789-.29308.11695-.10925-.00095-.21375-.04477-.29101-.12203-.07725-.07725-.12107-.18176-.12202-.29101-.00095-.10924.04105-.2145.11695-.29308l2.20541-2.20542Z" fill="#404BF2"/>
       </svg>
       </a></td>
			</tr>`;
            i++;
        });

        t += `</tbody></table></div>`;
        return t;
    }

};
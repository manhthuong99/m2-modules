/**********************************************************************
 * Momo payment
 *
 * @copyright Copyright © ManhThuong99. All rights reserved.
 * @author   thuongnm(mthuong03@gmail.com)
 */
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';

        rendererList.push(
            {
                type: 'momo',
                component: 'ManhThuong99_MomoWallet/js/view/payment/method-renderer/momo-wallet'
            }
        );

        /**
         * Add view logic here if needed
         */

        return Component.extend({});
    }
);

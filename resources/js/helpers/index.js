import axios from "axios";
import moment from 'moment';

export default function useHelpers() {

    /////////////////////////////////////////////////////////////////////
    // Format money based on integer or floating input
    // ===============================================
    // Possible inputs are:
    // value:                 Numerical input (required)
    // locale:                Language and country information, such as 'en' or 'en-US'
    // currencyCode:          3-character cdde from ISO 4217
    // subunitsValue:         Value is denominated in subunits, such as cents
    // subunitsToUnits:       Overrides the minor unit value from ISO 4217. The "subunitsValue"
    //                        option is redundant if you enter a value for this
    // hideSubunits:          Set this to true if you don't want to display the subunits
    // supplementalPrecision: Allows you to display partial subunits . This is ignored if
    //                        you specify "hideSubunits=true"
    /////////////////////////////////////////////////////////////////////
    const formatMoney = (
        value,
        locale = "fr-FR",
        currencyCode = "EUR",
        subunitsValue,
        subunitsToUnit,
        hideSubunits,
        supplementalPrecision
    ) => {
        let ret = 0;
        if (Number.isFinite(value)) {
        try {
            let numFormat = new Intl.NumberFormat(locale, {
            style: "currency",
            currency: currencyCode,
            });
            let options = numFormat.resolvedOptions();
            let fraction_digits = options.minimumFractionDigits;
            if (subunitsToUnit > 1) {
            value = value / subunitsToUnit;
            } else if (subunitsValue == true) {
            value = value / 10 ** options.minimumFractionDigits;
            }
            if (hideSubunits == true) {
            numFormat = new Intl.NumberFormat(locale, {
                style: "currency",
                currency: currencyCode,
                minimumFractionDigits: 0,
                maximumFractionDigits: 0,
            });
            } else if (supplementalPrecision > 0) {
            numFormat = new Intl.NumberFormat(locale, {
                style: "currency",
                currency: currencyCode,
                minimumFractionDigits: options.minimumFractionDigits + supplementalPrecision,
                maximumFractionDigits: options.maximumFractionDigits + supplementalPrecision,
            });
            }
            ret = numFormat.format(value);
        } catch (err) {
            ret = err.message;
        }
        } else {
        ret = "#NaN!";
        }
        return ret;
    }

    const formatDate = (sDate, sFormat = 'DD/MM/YYYY HH:mm') => {
        return moment(sDate).format(sFormat);
    }

    const saveOrder = () => {
        axios.post(route('orders.save'));
    }

    return {
        formatMoney,
        formatDate,
        saveOrder,
    }
}
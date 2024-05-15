function formatToBestSizeUnit(t, e = 2) {
    if (!+t) {
        return {
            value: 0,
            unit: "B"
        }
    }

    const a = e < 0 ? 0 : e
    const o = Math.floor(Math.log(t) / Math.log(1024));

    return {
        value: parseFloat((t / Math.pow(1024, o)).toFixed(a)),
        unit: ["B", "KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"][o]
    }
}

function displayCounterValue(className, counterValue, counterUnit = "", decimals = 2) {

    // wrap in try/catch to display N/A in case of error (and avoid infinite loading)
    try {
        
        if (counterValue == null || typeof counterValue != 'number') {
            document.getElementById(className).innerText = "N/A";
            return
        }

        // apply toFixed only if number is a decimal, to avoid 520.00 in output value
        if (counterValue % 1 == 0) {
            if (counterUnit != "") {
                document.getElementById(className).innerText = counterValue + " " + counterUnit;
            } else {
                document.getElementById(className).innerText = counterValue;
            }
        } else {
            if (counterUnit != "") {
                document.getElementById(className).innerText = counterValue.toFixed(decimals) + " " + counterUnit;
            } else {
                document.getElementById(className).innerText = counterValue.toFixed(decimals);
            }
        }

        document.getElementById(className).classList.add("counter");
        document.getElementById(className).classList.add("counter-number");

    } catch (t) {
        document.getElementById(className).innerText = "N/A";
    }
}

async function loadCidgStats() {
    try {
        const t = await fetch("https://service.cidgravity.com/public/v1/get-cidg-stats"),
        e = await t.json();

        if (200 === t.status) {
            displayCounterValue("clientsServed", e.result.clientServed);
            displayCounterValue("transactionCompleted", e.result.transactionsCompleted / 1e6, "M");
            displayCounterValue("storageProvidersEngaged", e.result.storageProvidersEngaged);
            
            // for currentLiveData, convert value from bytes to best unit (TiB, GiB, PiB ...)
            const currentLiveData = formatToBestSizeUnit(e.result.currentLiveData);
            displayCounterValue("currentLiveData", currentLiveData.value, currentLiveData.unit)
        }
    } catch (t) {
        console.error(t)
    }
}

// load stats only for homepage (index.html)
// for this, we use specific div id called "with-stats"
$(document).ready(function ($) {
    if ($('#with-stats').length) {
        loadCidgStats()
    }
})
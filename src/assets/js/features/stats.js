function formatToBestSizeUnit(t, e = 2) {
    if (!+t) return "0 Bytes";
    const a = e < 0 ? 0 : e,
        o = Math.floor(Math.log(t) / Math.log(1024));
    return {
        value: parseFloat((t / Math.pow(1024, o)).toFixed(a)),
        unit: ["Bytes", "KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"][o]
    }
}

function displayCounterValue(t, e, a, o = 2) {
    document.getElementById(t).innerText = e.toFixed(o), document.getElementById(t).classList.add("counter"), document.getElementById(t).classList.add("counter-number"), document.getElementById(t + "Description").innerText = a
}
async function loadCidgStats() {
    try {
        const t = await fetch("https://penguin-stirring-ghastly.ngrok-free.app/v1/get-cidg-stats"),
            e = await t.json();
        if (200 === t.status) {
            displayCounterValue("storageDeals", e.result.totalStorageDeals / 1e6, "number of storage deals analysed"), displayCounterValue("retrievalDeals", e.result.totalRetrievalDeals / 1e6, "number of retrieval deals analysed");
            const t = formatToBestSizeUnit(e.result.totalActiveVolume);
            displayCounterValue("activeVolume", t.value, t.unit + " of active deals analysed")
        }
    } catch (t) {
        console.error(t)
    }
}
window.onload = loadCidgStats();
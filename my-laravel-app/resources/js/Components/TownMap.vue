<template>
    <div id="ilocos-norte-map" class="shadow-sm border-0" style="height: 500px; width: 100%; border-radius: 1rem; z-index: 1; background-color: #f8f9fa;"></div>
</template>

<script setup>
import { onMounted } from 'vue';
import L from 'leaflet';
import axios from 'axios';

const props = defineProps({
    towns: Array
});

const emit = defineEmits(['town-clicked']);

let map = null;
let geojsonLayer = null;

// Helper to find our town object from the GeoJSON property ADM3_EN
const findTown = (geoName) => {
    // GeoName e.g. "Laoag City" or "Paoay"
    const normalizedGeoName = geoName.toLowerCase().replace(' city', '').replace(/ /g, '-');
    return props.towns.find(t => {
        const normalizedTownSlug = t.slug.replace('-city', '');
        return normalizedTownSlug === normalizedGeoName || t.name.toLowerCase() === geoName.toLowerCase();
    });
};

const getStyle = (feature) => {
    const town = findTown(feature.properties.ADM3_EN);
    let fillColor = '#e2e8f0'; // Default Locked (Grey)
    let fillOpacity = 0.8;
    let color = '#ffffff'; // Border color

    if (town) {
        if (town.progress_status === 'completed') {
            fillColor = '#28a745'; // Green
        } else if (town.progress_status === 'available') {
            fillColor = '#007bff'; // Blue
        }
    }

    return {
        fillColor: fillColor,
        weight: 2,
        opacity: 1,
        color: color,
        fillOpacity: fillOpacity
    };
};

onMounted(async () => {
    // Initialize map with a clean white/grey background and disable zoom/pan to lock the view
    map = L.map('ilocos-norte-map', {
        zoomControl: true,
        dragging: true,
        scrollWheelZoom: false,
        doubleClickZoom: true,
        boxZoom: true,
        tap: true
    }).setView([18.1960, 120.5927], 9);

    // Notice: NO L.tileLayer is added, so the background remains clean

    try {
        const response = await axios.get('/assets/data/ilocos_norte.json');
        const geoData = response.data;

        geojsonLayer = L.geoJSON(geoData, {
            style: getStyle,
            onEachFeature: (feature, layer) => {
                const town = findTown(feature.properties.ADM3_EN);
                const status = town ? town.progress_status : 'locked';
                const townName = town ? town.name : feature.properties.ADM3_EN;
                
                let statusHtml = '';
                let clickText = '';
                if (status === 'completed') {
                    statusHtml = '<i class="fas fa-check-circle" style="color: #28a745;"></i> <span style="color: #28a745; font-weight: bold; font-size: 11px;">COMPLETED</span>';
                    clickText = '<br/><span style="font-size: 10px; color: #6c757d;"><i class="fas fa-mouse-pointer"></i> Click to view</span>';
                } else if (status === 'available') {
                    statusHtml = '<i class="fas fa-unlock" style="color: #007bff;"></i> <span style="color: #007bff; font-weight: bold; font-size: 11px;">AVAILABLE</span>';
                    clickText = '<br/><span style="font-size: 10px; color: #6c757d;"><i class="fas fa-mouse-pointer"></i> Click to view</span>';
                } else {
                    statusHtml = '<i class="fas fa-lock" style="color: #6c757d;"></i> <span style="color: #6c757d; font-weight: bold; font-size: 11px;">LOCKED</span>';
                    clickText = '';
                }

                // Tooltip on hover
                layer.bindTooltip(`<div style="line-height: 1.4;"><strong style="font-size: 14px;">${townName}</strong><br/>${statusHtml}${clickText}</div>`, {
                    sticky: true,
                    className: 'custom-tooltip'
                });

                // Hover interactions
                layer.on({
                    mouseover: (e) => {
                        const layer = e.target;
                        layer.setStyle({
                            fillOpacity: 1,
                            weight: 3,
                            color: '#0a472e'
                        });
                        if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                            layer.bringToFront();
                        }
                    },
                    mouseout: (e) => {
                        geojsonLayer.resetStyle(e.target);
                    },
                    click: (e) => {
                        if (town && town.progress_status !== 'locked') {
                            emit('town-clicked', town.slug);
                        }
                    }
                });
            }
        }).addTo(map);

        // Fit map bounds exactly to Ilocos Norte shape
        map.fitBounds(geojsonLayer.getBounds(), { padding: [20, 20] });
        
    } catch (error) {
        console.error("Error loading GeoJSON map data:", error);
    }
});
</script>

<style>
.custom-tooltip {
    background-color: white;
    border: none;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    padding: 8px 12px;
    font-family: inherit;
    text-align: center;
}
.custom-tooltip::before {
    display: none; /* Hide default leaflet tooltip arrow */
}
</style>

import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const DEFAULT_PALETTE = [
    '#22c55e',
    '#06b6d4',
    '#3b82f6',
    '#a855f7',
    '#ec4899',
    '#f97316',
    '#facc15',
    '#14b8a6',
    '#8b5cf6',
    '#fb7185',
    '#60a5fa',
    '#34d399',
];

function normalizeDatasetColors(dataset, palette = DEFAULT_PALETTE) {
    if (!dataset || Array.isArray(dataset.backgroundColor) || dataset.backgroundColor) return dataset;

    return {
        ...dataset,
        backgroundColor: palette,
    };
}

function buildConfig(rawConfig) {
    const config = { ...rawConfig };

    config.options = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position: 'bottom' },
            ...rawConfig?.options?.plugins,
        },
        ...rawConfig?.options,
    };

    if (config?.data?.datasets?.length) {
        config.data = {
            ...config.data,
            datasets: config.data.datasets.map((dataset, index) => {
                const palette = DEFAULT_PALETTE.map((color, offset) => DEFAULT_PALETTE[(offset + index) % DEFAULT_PALETTE.length]);
                return normalizeDatasetColors(
                    {
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        ...dataset,
                    },
                    palette,
                );
            }),
        };
    }

    return config;
}

export function initCharts() {
    const nodes = Array.from(document.querySelectorAll('[data-chart-config]'));
    if (!nodes.length) return;

    for (const node of nodes) {
        const canvas = node.tagName === 'CANVAS' ? node : node.querySelector('canvas');
        if (!canvas) continue;

        let config;
        try {
            config = JSON.parse(canvas.dataset.chartConfig || node.dataset.chartConfig || '{}');
        } catch {
            continue;
        }

        const data = config?.data?.datasets?.[0]?.data || [];
        const labels = config?.data?.labels || [];
        const type = config?.type || 'bar';
        const needsLabels = !['scatter', 'bubble'].includes(type);

        if (!data.length) continue;
        if (needsLabels && !labels.length) continue;

        const finalConfig = buildConfig(config);
        new Chart(canvas, finalConfig);
    }
}

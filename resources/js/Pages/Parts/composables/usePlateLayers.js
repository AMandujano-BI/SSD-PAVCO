export const MAX_PLATE_LAYERS = 6;

export const emptyPlateLayer = () => ({
    plate_type_id: null,
    thickness: "",
    thickness_unit: null,
    per: "",
    temp: "",
    ph: "",
    diptime: "",
});

const fromServerLayer = (layer) => ({
    plate_type_id: layer.plate_type_id,
    thickness: layer.thickness ?? "",
    thickness_unit: Number(layer.thickness_unit) || null,
    per: layer.per ?? "",
    temp: layer.temp ?? "",
    ph: layer.ph ?? "",
    diptime: layer.diptime ?? "",
});

export const plateLayersFromPart = (part) => {
    const layers = (part.plate_layers ?? []).map(fromServerLayer);
    if (layers.length) return layers;

    if (part.plate_types_id) {
        return [
            {
                ...emptyPlateLayer(),
                plate_type_id: part.plate_types_id,
                thickness: part.plateThick ?? "",
                thickness_unit: Number(part.typePlateThick) || null,
            },
        ];
    }

    return [emptyPlateLayer()];
};

export const clonePlateLayers = (layers) => layers.map((layer) => ({ ...layer }));

const unitLabel = (unit) => (unit == 1 ? "microns" : unit == 2 ? "mils" : null);

const plateLayerLabel = (name, thickness, unit) => {
    const unitText = unitLabel(unit);
    const thicknessText = thickness == null || thickness === "" ? null : thickness;
    const suffix = unitText == null || thicknessText == null ? "" : ` - ${thicknessText} ${unitText}`;
    return `${name}${suffix}`;
};

export const plateLayersCellHtml = (row) => {
    const layers = (row.plate_layers ?? []).filter((layer) => layer.plate_type?.name);
    if (layers.length) {
        const lines = layers.map((layer) =>
            plateLayerLabel(layer.plate_type.name, layer.thickness, layer.thickness_unit)
        );
        return `<td>${lines.join("<br>")}</td>`;
    }
    if (row.plate_type?.name != undefined) {
        return `<td>${plateLayerLabel(row.plate_type.name, row.plateThick, row.typePlateThick)}</td>`;
    }
    return "<td></td>";
};

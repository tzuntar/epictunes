const autoFillUsername = function (sourceFieldId, targetFieldId) {
    const src = document.getElementById(sourceFieldId).value;
    if (src.trim().length < 1)
        return;
    document.getElementById(targetFieldId).value = src.trim()
            .replace(' ', '.')
            .toLowerCase()
        + Math.floor(1 + Math.random() * 50);
};

const getImgMutedColorRgb = function (sourceTagId) {
    const colors = new Vibrant(document.getElementById(sourceTagId));
    const rgb = colors.swatches().DarkMuted.rgb;
    return `rgb(${rgb[0]}, ${rgb[1]}, ${rgb[2]})`;
}

const getImgVibrantColorRgb = function (sourceTagId) {
    try {
        const colors = new Vibrant(document.getElementById(sourceTagId));
        const rgb = colors.swatches().DarkVibrant.rgb;
        return `rgb(${rgb[0]}, ${rgb[1]}, ${rgb[2]})`;
    } catch (ignored) {
        return 'rgb(0, 0, 0)';
    }
}

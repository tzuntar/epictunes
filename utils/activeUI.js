const autoFillUsername = function (sourceFieldId, targetFieldId) {
    const src = document.getElementById(sourceFieldId).value;
    if (src.trim().length < 1)
        return;
    document.getElementById(targetFieldId).value = src.trim()
            .replace(' ', '.')
            .toLowerCase()
        + Math.floor(1 + Math.random() * 50);
};

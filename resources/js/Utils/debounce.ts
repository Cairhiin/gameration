export default function debounce(
    func: Function,
    delay: number = 600,
    immediate: boolean = false
) {
    let timeout: NodeJS.Timeout;

    return function () {
        const context = this;
        const args = arguments;
        const later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, delay);
        if (callNow) func.apply(context, args);
    };
}

/**
 * Capitalizes the first letter of a given string.
 *
 * @param {string} string - The string to capitalize.
 * @return {string} The capitalized string.
 */
export default function capitalize(string: string): string {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

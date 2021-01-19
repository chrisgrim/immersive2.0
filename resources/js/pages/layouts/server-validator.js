export const MatchValidator = (stringToMatch) => {
  return (value, component) => value === stringToMatch;
}
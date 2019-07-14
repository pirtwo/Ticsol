import { helpers } from "vuelidate/lib/validators";

export const before = (date) =>
  helpers.withParams(
    { type: "before", date: date },
    value => value < date
  );

export const after = (date) =>
  helpers.withParams(
    { type: "after", date: date },
    value => value > date
  );

export const between = (min, max) =>
  helpers.withParams(
    { type: "between", min: min, max: max },
    value => value >= min && value <= max
  );


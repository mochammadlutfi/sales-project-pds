
import { AbilityBuilder, createMongoAbility } from '@casl/ability';
const { can, rules } = new AbilityBuilder(createMongoAbility);
export default rules;
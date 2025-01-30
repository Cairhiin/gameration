import type { User } from "@/Types";

const isAdmin = (user: User): boolean =>
    user?.roles.filter((role) => role.name.includes("admin")).length > 0;

const isModerator = (user: User): boolean =>
    user?.roles.filter((role) => role.name.includes("moderator")).length > 0;

const canModerate = (user: User): boolean => isAdmin(user) || isModerator(user);

export { canModerate, isAdmin, isModerator };

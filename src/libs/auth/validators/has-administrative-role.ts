import { Role } from '@prisma/client';

export function hasAdministrativeRole(role: Role | undefined) {
  return role === Role.ADMINISTRATOR || role === Role.OWNER;
}

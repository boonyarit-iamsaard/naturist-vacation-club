<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $membership_price_id
 * @property int $room_discount Discount percentage for room prices
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\MembershipPrice $price
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserMembership> $userMemberships
 * @property-read int|null $user_memberships_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereMembershipPriceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereRoomDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Membership withoutTrashed()
 */
	class Membership extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $female
 * @property int $male
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Membership> $memberships
 * @property-read int|null $memberships_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipPrice onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipPrice whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipPrice whereFemale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipPrice whereMale($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipPrice withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipPrice withoutTrashed()
 */
	class MembershipPrice extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $membership_code
 * @property int $last_assigned_sequence
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipSequence newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipSequence newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipSequence onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipSequence query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipSequence whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipSequence whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipSequence whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipSequence whereLastAssignedSequence($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipSequence whereMembershipCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipSequence whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipSequence withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MembershipSequence withoutTrashed()
 */
	class MembershipSequence extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $image
 * @property string $gender
 * @property string|null $phone
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $first_login_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\UserMembership|null $activeUserMembership
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\UserMembership> $userMemberships
 * @property-read int|null $user_memberships_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereFirstLoginAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User withoutTrashed()
 */
	class User extends \Eloquent implements \Filament\Models\Contracts\FilamentUser, \Illuminate\Contracts\Auth\MustVerifyEmail {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $membership_id
 * @property string $membership_number
 * @property string $user_name
 * @property string $user_email
 * @property string $user_gender
 * @property string $membership_name
 * @property int $membership_price_at_joining
 * @property \Illuminate\Support\Carbon $start_date
 * @property \Illuminate\Support\Carbon $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Membership|null $membership
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereMembershipId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereMembershipName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereMembershipNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereMembershipPriceAtJoining($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereUserEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereUserGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership whereUserName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|UserMembership withoutTrashed()
 */
	class UserMembership extends \Eloquent {}
}

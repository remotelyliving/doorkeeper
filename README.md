[![Build Status](https://travis-ci.org/remotelyliving/doorkeeper.svg?branch=master)](https://travis-ci.org/remotelyliving/doorkeeper)
[![Coverage Status](https://coveralls.io/repos/github/remotelyliving/doorkeeper/badge.svg?branch=master)](https://coveralls.io/github/remotelyliving/doorkeeper?branch=master)
[![Latest Stable Version](https://poser.pugx.org/remotelyliving/doorkeeper/v/stable)](https://packagist.org/packages/remotelyliving/doorkeeper)
[![Total Downloads](https://poser.pugx.org/remotelyliving/doorkeeper/downloads)](https://packagist.org/packages/remotelyliving/doorkeeper)
[![License](https://poser.pugx.org/remotelyliving/doorkeeper/license)](https://packagist.org/packages/remotelyliving/doorkeeper)
[![Monthly Downloads](https://poser.pugx.org/remotelyliving/doorkeeper/d/monthly)](https://packagist.org/packages/remotelyliving/doorkeeper)

# Doorkeeper: a feature toggle

### The birth of a Feature Toggle
>Picture the scene. You're on one of several teams working on a sophisticated town planning simulation game. Your team is responsible for the core simulation engine. You have been tasked with increasing the efficiency of the Spline Reticulation algorithm. You know this will require a fairly large overhaul of the implementation which will take several weeks. Meanwhile other members of your team will need to continue some ongoing work on related areas of the codebase.
You want to avoid branching for this work if at all possible, based on previous painful experiences of merging long-lived branches in the past. Instead, you decide that the entire team will continue to work on trunk, but the developers working on the Spline Reticulation improvements will use a Feature Toggle to prevent their work from impacting the rest of the team or destabilizing the codebase.

https://martinfowler.com/articles/feature-toggles.html

### Enter Doorkeeper (if you can)

There are a few feature toggle framework and libraries out there already. And many of them are fine.
Doorkeeper was born our of a previous experience with one and wishing what it could be.

### Dynamic Usage

Doorkeeper is storage agnostic, and has a few helpers to help translate what you decide to persist
and how you want to load it. But however you choose to setup it's config, you can toggle your feature on and off
by changing that configuration.

### Wiring it up

```php
$feature_set = $feature_set_repository->getSet();

// Doorkeper takes in a feature set and an audit log if you want to log access results
$doorkeeper  = new Doorkeeper($feature_set, $logger);

// If access to a requestor from a service container, you can set here
// a request can only be set once on an instance as it's supposed to persist immutably during the life of the call request
$doorkeeper->setRequestor($service_container['requestor']);
```

### Usage

```php
if ($doorkeeper->grantsAccessTo('someNewFeature') {
    return $this->doNewFeatureStuff();
}

// OR if you want to bypass the instance Requestor that was set and create another
// This is useful for jobs where you're hydrating batches of user data and processing them
$other_requestor = (new Requestor)->withUserId($user_id);

if ($doorkeeper->grantsAccessToRequestor('someNewFeature', $other_requestor)) {
    return $this->doNewFeatureStuff();
}
```

### Requestor

A Requestor is the one asking to access the feature. They must pass the Doorkeeper's strict house rules to enter.
To see if a requestor is allowed access, they must present Identifications.

`RemotelyLiving\Doorkeeper\Identifications`
- HttpHeader - based on a `doorkeeper` header present in a request.
- IntegerId (user id) - Based on the logged in user id
- IpAddress - based on the Requestor's ip address
- StringHash - Some flexibility here. Set it to whatever you want.
- Environment - Based on the app environment the Requestor is in.

The Requestor is immutable. It should not be changed anywhere in the call stack. 
That would produce less than consistent results depending on where the query takes place.

The Requestor is best wired up and set in a service container. There are several convenience methods
to set identities. Check em out.

### Rules

`RemotelyLiving\Doorkeeper\Rules`
There are several types of Rules to use when defining access to a feature

- Environment: can be set with the environment name that the feature is available in

- Http Header: matches a specific value from a custom `doorkeeper` header you can choose to send.
 *The request identification on a Requestor must be registered to have a positive match

- Ip Address: this is a specific IP address the feature is available to. Helpful for in-office access.
 *It only works if the Requestor has an IpAddress identification registered
  
- Percentage: a percentage of requests to allow through to the feature

- Random: chaos monkey. This rule randomly allows access.

- StringHash: this is an arbitrary string. It works well for request query params, username, etc.
 *This only works if a StringHash identification is set on the Requestor

- Time After: allows for access to a feature only *after* the set time on the rule.

- User Id: this rule allows for specific user access to a feature.
 *User Id only works if the Request has a user id identification registered to them
 
***Prerequisistes
Rules can be dependant on other rules for any other feature.

```php
$rule->setPrerequisite($someOtherRule);
```

That prerequisite must be satisfied before the other rule is even evaluated.

### Feature

`RemotelyLiving\Doorkeeper\Features`

A Feature is what a Requestor is asking for by name. It can have 0-n Rules around it.
A Feature has a top level on/off switch called `enabled` that can bypass any rules.

```php
$feature = new Feature('theFeatureName', $enabled, $rules);
```

Doorkeeper gets the rules from a feature and evaluates them. If they require a specific Identification
Doorkeeper looks into the Requestor to see if they have the right Identifications required by a rule

***The first rule to be satisfied grants access***

Keep that in mind when setting rules up on a feature.

If you say "only this ip address is allowed, but also the DEV environment."

All requests with that ip address OR in the DEV environment will be allowed.

### Feature Set

A feature set object is the bread and butter of Doorkeeper. It's a collection of Features and rules and makes for easy
caching. It is the complete set of what defines access to features.

### Feature Set Repository and Caching

`Remotelyliving\Doorkeeper\Features`

Obviously something that fires up for every request that uses dynamic config data can be costly.

If you're using memcached or redis and a PSR6 cache library. you can cache and retrieve the Feature Set in the `Features\SetRepository`.
 
You can build the Feature Set from arrays using the `Features\Set::createFromArray()`

That array can come from anywhere: relational database, cache, config, xml, whatever.

Checkout that factory method to see the schema of the array that needs to be passed in.

How you choose to persist Features is up to you. But there are two things you're responsible for if using the `Features\SetRepository`

1. Clearing the cache when any member of a Feature Set is changed via `deleteFeatureSet`
2. Providing a callback that can provide a hydrated Feature Set to the `get('featureId', $returnsAFeatureSetIfNeedBe)` method

Doorkeeper also has a runtime cache that caches answers in memory to help as well.
For persistent applications you'll need to call `Doorkeeper::flushRuntimeCache()`
any time a Rule or Feature is updated.





## Explanation

The main idea is designing the domain model as close to task language as possible.
In some places I had to guess the ubiquitous language. <br />
In my solution I have modeled **Transportation system** which consists of **Parking**,**Road** and **Sidewalk**<br />
**Groups** can gather on **Sidewalk** and get into **Vehicle**, they can exit **Vehicle** and dissipate on **Sidewalk**. <br/>
**Vehicle** in turn can leave the **Parking** and get to **Road** and vice versa.

### Improvements
* The initial idea included loading the aggregate (Transportation::__construct) via Redis cache. 
Domain events were supposed to be async and go via RabbitMQ queue. 
I have removed it because I needed to simplify environment as much as possible for cd/ci part with which I still have problems.
However, after solving the pipeline problem, returning Redis and RabbitMQ is the next step
* I consider my usage of traits a code smell, redundant coupling between Domain and Infrastructure.
* I am used to having Postgres as a default DB for Symfony. Shortly after starting tackling the problem I've realised that MongoDB would be much better tool.
* Instead of basically one business event that triggers handling of everything - I could do chain of events with much more primitive reactions for each, but tradeoff was not worth it even for demo purposes

## Installation
create containers, install packages, create databases:
```
make up
```
run tests:
```
make test-all
```

## Problem

Design and implement a system to manage electric vehicle (EV) pooling.

Company recently opened its new factory close to its headquarters. Communication
between teams is key and we often need to move from one place to another.
To achieve that, we have a fleet of EVs ready to use for our employees.
As saving energy is one of our main goals, we propose sharing cars with multiple
groups of people. This is an opportunity to optimize the use of resources by introducing car
pooling.

You have been assigned to build the car availability service that will be used
to track the available seats in cars.

Cars have a different amount of seats available. They can accommodate groups of
up to 4, 5 or 6 people.

People request cars in groups of 1 to 6. People in the same group want to ride
in the same car. You can assign any group to any car that has enough empty seats
for them. If it's not possible to accommodate them, they're willing to wait until
there's a car available for them. Once a car is available for a group, they should immediately 
enter and drive the car. You cannot ask them to change the car (i.e. swap them to make space for another group). 
The trip order should be "First come, first serve".

For example, a group of 6 people is waiting for a car. They cannot enter a car with less than 6 available seats 
(you can not split the group), so they need to wait. This means that smaller groups after them could enter a car with 
fewer available seats before them.

## API

To simplify the challenge and remove language restrictions, this service must
provide a REST API that will be used to interact with it.

This API must comply with the following contract:

### GET /status

Indicate the service has started up correctly and is ready to accept requests.

Responses:

* **200 OK** When the service is ready to receive requests.

### PUT /evs

Load the list of available EVs in the service and remove all previous data
(existing journeys and EVs). This method may be called more than once during
the life cycle of the service.

**Body** _required_ The list of EVs to load.

**Content Type** `application/json`

Sample:

```json
[
  {
    "id": 1,
    "seats": 4
  },
  {
    "id": 2,
    "seats": 6
  }
]
```

Responses:

* **200 OK** When the list is registered correctly.
* **400 Bad Request** When there is a failure in the request format, expected
  headers, or the payload can't be unmarshalled.

### POST /journey

A group of people requests to perform a journey.

**Body** _required_ The group of people that wants to perform the journey

**Content Type** `application/json`

Sample:

```json
{
  "id": 1,
  "people": 4
}
```

Responses:

* **200 OK** or **202 Accepted** When the group is registered correctly.
* **400 Bad Request** When there is a failure in the request format or the
  payload can't be unmarshalled.

### POST /dropoff

A group of people requests to be dropped off whether they traveled or not.

**Body** _required_ The ID of the group

**Content Type** `application/json`

Sample:

```json
{
  "id": 1
}
```

Responses:

* **200 OK** or **204 No Content** When the group is unregistered correctly.
* **404 Not Found** When the group cannot be found.
* **400 Bad Request** When there is a failure in the request format or the
  payload can't be unmarshalled.

### POST /locate

Given a group ID such as `ID=X`, return the car the group is traveling
with, or no car if they are still waiting to be served.

**Body** _required_ The ID of the group

**Content Type** `application/json`

Sample:

```json
{
  "id": 1
}
```

**Accept** `application/json`

Responses:

* **200 OK** With the car as the payload when the group is assigned to a car.
* **204 No Content** When the group is waiting to be assigned to a car.
* **404 Not Found** When the group cannot be found.
* **400 Bad Request** When there is a failure in the request format or the
  payload can't be unmarshalled.



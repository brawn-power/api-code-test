# Workout Progress API Endpoint

## Overview

Users would like to be able to track their workout progress over time. We need to create a new API endpoint to enable our mobile app to consume the data and show a graph. The endpoint should return volume (`reps` * `weight`) per workout session. The data should be restricted to the authenticated user. It should be ordered by the latest workout session first. The data should be paginated for performance reasons.

## Data Structure

- `workout_sessions`: Contains high-level data about workout sessions, such as when it occurred and which user it belongs to.
- `sets`: Contains set-level data, including the number of reps, lift ID, weight, and workout session ID.
- `lifts`: Contains data about the lifts performed.

## Filtering Parameters

- `lift`: Filter by the lift performed.
- `reps`: Filter by the number of reps.
- `dates`: Filter by date range.

*Note: Future parameters may be added.*

## Pagination

- Results are paginated, showing 15 entries per page.
- Entries are ordered by the latest first.

## Additional Data

The paginated response should include the volume by workout session. Additionally, the following stats are also required.
- Max volume (reps x weight) for a single session across all time.
- Max weight lifted across all time.

## Data Calculation

- Volume (weight x reps) is calculated per workout session.

## Error Handling

- Utilizes Laravel's error handling for graceful error messages.
- Authentication issues are handled by the Laravel framework.

## Parameter Validation

- Validate incoming parameters to ensure they meet the expected criteria.


Feel free to explore the code for a better understanding of the data structure.

## Brief ##
Create the required endpoint and classes to create a solution to the problem listed above. Please take a fork of this repo and submit a pull request with your solution. Please include a brief explanation of your solution and any assumptions you made in your pull request.

This task should take no longer than 2 hours. If there is anything you run out of time to complete please list on the PR what you would have done if you had more time.

If you have any questions, or anything is unclear please reach out for additional support.

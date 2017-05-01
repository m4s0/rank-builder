#!/usr/bin/python

prior = 0.5  # prior prob
w = 0.25  # prior weight (0%-100%)

up = 0.
down = 10.
avg = (1-w)*up/(up+down) + w*prior
print(avg)

up = 0.
down = 50.
avg2 = (1-w)*up/(up+down) + w*prior
print(avg2)


FAKE_UP = 10.
FAKE_DOWN = 10.

up_votes = 0.
down_votes = 0.
avg = (up_votes + FAKE_UP)/(up_votes + down_votes + FAKE_UP + FAKE_DOWN)
print(avg)

up_votes = 0.
down_votes = 1e50
avg = (up_votes + FAKE_UP)/(up_votes + down_votes + FAKE_UP + FAKE_DOWN)
print(avg)

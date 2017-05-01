#!/usr/bin/env python
import argparse
from math import sqrt

def _confidence(ups, downs):
    n = ups + downs

    if n == 0:
        return 0

    z = 1.281551565545
    p = float(ups) / n

    left = p + 1/(2*n)*z*z
    right = z*sqrt(p*(1-p)/n + z*z/(4*n*n))
    under = 1+1/n*z*z

    return (left - right) / under

def confidence(ups, downs):
    if ups + downs == 0:
        return 0
    else:
        return _confidence(ups, downs)

def main(args):
    ups = args.ups
    downs = args.downs
    c = confidence(float(ups), float(downs))
    print("Score " + str(c))


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description='-*- sorts -+-')
    parser.add_argument('--ups', action='store', default='', help='ups votes.')
    parser.add_argument('--downs', action='store', default='', help='downs votes.')

    args = parser.parse_args()
    main(args)

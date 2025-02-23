import random

def generate_random_number(limit):
    return random.randint(1, limit)

def main():
    limit = 100
    random_number = generate_random_number(limit)
    print(f"The random number is: {random_number}")

if __name__ == "__main__":
    main()

# pvpgn-tracker daemon

import argparse
import json
import socket
import struct
import time

def parse_args():
    parser = argparse.ArgumentParser(description='pvpgn-tracker daemon')

    parser.add_argument('--expire', type=int, nargs=1, default=600,
                        help='seconds to wait for expiration (default: 600)')

    parser.add_argument('--interface', nargs=1, default='0.0.0.0',
                        help='the interface to bind to (default: 0.0.0.0)')

    parser.add_argument('--state-file', nargs=1, default='/dev/shm/bntrackd.json',
                        help='the shared state json file (default: /dev/shm/bntrackd.json)')

    parser.add_argument('--port', type=int, nargs=1, default=6114,
                        help='the port to bind to (default: 6114)')

    return parser.parse_args()

def main():
    args = parse_args()

    solicitations_sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)

    try:
        solicitations_sock.bind((args.interface, args.port))
        print("solicitations socket listening on %s:%i" % (args.interface, args.port))

    except Exception as e:
        print("exception occurred while binding solicitations socket. Exception: %s" % e)
        solicitations_sock.close()
        return

    state = load_state(args.state_file)

    while True:
        data, addr = solicitations_sock.recvfrom(464)
        solicitation = parse_datagram(data, addr)
        handle_solicitation(solicitation, state)
        prune_solicitations(state, args.expire)
        save_state(args.state_file, state)

def load_state(filename):
    try:
        with open(filename) as f:
            data = json.load(f)

    except IOError as e:
        data = {"servers":{}}

    return data

def save_state(filename, data):
    with open(filename, 'w') as f:
        json.dump(data, f)

def parse_datagram(data, addr):
    datasize = len(data)

    print("received %i byte(s) from %s:%i" % (datasize, addr[0], addr[1]))

    fmt = ">HHI32s16s32s64s64s96s64s64sIIIIII"

    if datasize != struct.calcsize(fmt):
        print("invalid message size, %i != %i!" % (datasize, struct.calcsize(fmt)))

    data_version, port, flags, software, version, platform, description, location, url, contact_name, contact_email, active_users, active_channels, active_games, uptime, total_games, total_logins = struct.unpack(fmt, data)

    software = software.rstrip('\0')
    version = version.rstrip('\0')
    platform = platform.rstrip('\0')
    description = description.rstrip('\0')
    location = location.rstrip('\0')
    url = url.rstrip('\0')
    contact_name = contact_name.rstrip('\0')
    contact_email = contact_email.rstrip('\0')

    dataobj = {
        "data_version": data_version,
        "solicitation_time": time.time(),
        "ip_address": addr[0],
        "port": port,
        "flags": flags,
        "software": software,
        "version": version,
        "platform": platform,
        "description": description,
        "location": location,
        "url": url,
        "contact_name": contact_name,
        "contact_email": contact_email,
        "active_users": active_users,
        "active_channels": active_channels,
        "active_games": active_games,
        "uptime": uptime,
        "total_games": total_games,
        "total_logins": total_logins,
    }

    print(json.dumps(dataobj))

    return dataobj

def get_solicitation_id(ip_address, port):
    return (ip_address + str(port))

def handle_solicitation(data, state):
    solicitation_id = get_solicitation_id(data['ip_address'], data['port'])

    servers = state.get('servers')
    servers[solicitation_id] = data

def prune_solicitations(state, expire_after):
    current_time = time.time()
    new_servers = {}
    servers = state.get('servers')

    for key, server in servers.items():
        if server.get('solicitation_time') + expire_after > current_time:
            new_servers[key] = server

    state['servers'] = new_servers

main()
